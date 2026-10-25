<?php
session_start();
require_once 'db_config.php';
require_once 'audit_logger.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? ''; // Expecting role from hidden input or select

    if (empty($username) || empty($password)) {
        die(json_encode(['status' => 'error', 'message' => 'Please fill in all fields.']));
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Check if user is active
            if ($user['status'] !== 'active') {
                die(json_encode(['status' => 'error', 'message' => 'Your account is deactivated.']));
            }

            // Verify role if provided
            if (!empty($role)) {
                if ($role === 'admin' && ($user['role'] === 'admin' || $user['role'] === 'super_admin')) {
                    // Valid
                } else if ($user['role'] !== $role) {
                    die(json_encode(['status' => 'error', 'message' => 'Invalid role for this account.']));
                }
            }

            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            // Log successful login
            logAudit($pdo, $user['id'], $user['role'], 'Login', 'User successfully logged in.');

            // Redirect based on role
            $redirects = [
                'admin' => 'admin-dashboard.php',
                'super_admin' => 'admin-dashboard.php',
                'officer' => 'officer-dashboard.php',
                'candidate' => 'candidate-dashboard.php',
                'voter' => 'voter-dashboard.php'
            ];

            echo json_encode(['status' => 'success', 'redirect' => $redirects[$user['role']]]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid username or password.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
