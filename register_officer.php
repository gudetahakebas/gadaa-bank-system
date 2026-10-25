<?php
session_start();
require_once 'db_config.php';
require_once 'audit_logger.php';

// Check if admin
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) {
    die(json_encode(['status' => 'error', 'message' => 'Unauthorized access.']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_role = $_POST['user_role'] ?? 'officer';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $sex = $_POST['sex'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Security check: Only super_admin can register admins
    if ($user_role === 'admin' && $_SESSION['role'] !== 'super_admin') {
        die(json_encode(['status' => 'error', 'message' => 'Only Admin Boss can register new admins.']));
    }

    if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password) || empty($phone)) {
        die(json_encode(['status' => 'error', 'message' => 'Required fields missing.']));
    }

    // Phone Validation (Ethiopian Numbers)
    if (!preg_match('/^(?:\+251|0)[79]\d{8}$/', $phone)) {
        die(json_encode(['status' => 'error', 'message' => 'Invalid phone number format. Only Ethiopian numbers (+2519... / 09... / 07...) are allowed.']));
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $pdo->beginTransaction();

        // 1. Create User Account
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$username, $hashed_password, $user_role]);
        $user_id = $pdo->lastInsertId();

        // 2. Create Profile
        if ($user_role === 'admin') {
            $stmt = $pdo->prepare("INSERT INTO admins (user_id, first_name, last_name, email, phone) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $first_name, $last_name, $email, $phone]);
            $msg = 'Admin registered successfully.';
            $action = 'Registered Admin';
        } else {
            // Check if phone already exists in officers
            $stmtPhone = $pdo->prepare("SELECT id FROM officers WHERE phone = ?");
            $stmtPhone->execute([$phone]);
            if ($stmtPhone->rowCount() > 0) {
                die(json_encode(['status' => 'error', 'message' => 'Phone number already registered to another user.']));
            }

            $stmt = $pdo->prepare("INSERT INTO officers (user_id, first_name, last_name, sex, email, phone) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $first_name, $last_name, $sex, $email, $phone]);
            $msg = 'Officer registered successfully.';
            $action = 'Registered Officer';
        }

        $pdo->commit();
        
        // Log the action
        logAudit($pdo, $_SESSION['user_id'], $_SESSION['role'], $action, "Registered new $user_role username: $username");
        
        echo json_encode(['status' => 'success', 'message' => $msg]);
    } catch (PDOException $e) {
        $pdo->rollBack();
        if ($e->getCode() == 23000) { // Integrity constraint violation (duplicate username/email)
            echo json_encode(['status' => 'error', 'message' => 'Username, Email, or Phone already exists.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
}
?>
