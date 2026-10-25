<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['status' => 'error', 'message' => 'Not logged in.']));
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

try {
    $data = [];
    if ($role === 'candidate') {
        $stmt = $pdo->prepare("SELECT c.*, u.username FROM candidates c JOIN users u ON c.user_id = u.id WHERE u.id = ?");
        $stmt->execute([$user_id]);
        $data = $stmt->fetch();
        if ($data) $data['name'] = $data['first_name'] . ' ' . $data['middle_name'] . ' ' . $data['last_name'];
    } elseif ($role === 'voter') {
        $stmt = $pdo->prepare("SELECT v.*, u.username FROM voters v JOIN users u ON v.user_id = u.id WHERE u.id = ?");
        $stmt->execute([$user_id]);
        $data = $stmt->fetch();
        if ($data) $data['name'] = $data['first_name'] . ' ' . $data['middle_name'] . ' ' . $data['last_name'];
    } else {
        $stmt = $pdo->prepare("SELECT username, role FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $data = $stmt->fetch();
    }

    if ($data) {
        echo json_encode(['status' => 'success', 'data' => $data, 'role' => $role]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Profile not found.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
