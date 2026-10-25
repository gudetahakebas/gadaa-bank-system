<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) {
    die(json_encode(['status' => 'error', 'message' => 'Unauthorized access.']));
}

$role_filter = $_GET['role'] ?? 'all';

try {
    $sql = "SELECT a.id, u.username, a.role, a.action, a.details, a.ip_address, a.created_at 
            FROM audit_logs a 
            LEFT JOIN users u ON a.user_id = u.id";
            
    $params = [];
    if ($role_filter !== 'all') {
        $sql .= " WHERE a.role = ?";
        $params[] = $role_filter;
    }
    
    $sql .= " ORDER BY a.created_at DESC LIMIT 100"; // limit to recent 100

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $logs = $stmt->fetchAll();

    echo json_encode(['status' => 'success', 'data' => $logs]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>

