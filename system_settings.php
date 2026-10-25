<?php
session_start();
require_once 'db_config.php';
require_once 'audit_logger.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) {
    die(json_encode(['status' => 'error', 'message' => 'Unauthorized.']));
}

$action = $_GET['action'] ?? '';

try {
    if ($action === 'update_phase') {
        $phase = $_POST['phase'] ?? 'Registration';
        $stmt = $pdo->prepare("UPDATE system_settings SET setting_value = ? WHERE setting_key = 'election_status'");
        $stmt->execute([$phase]);
        
        // Log action
        logAudit($pdo, $_SESSION['user_id'], $_SESSION['role'], 'Update Election Phase', "Changed election phase to $phase");
        
        echo json_encode(['status' => 'success', 'message' => 'Election phase updated.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>

