<?php
session_start();
require_once 'db_config.php';
require_once 'audit_logger.php';

// Check if admin
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) {
    die(json_encode(['status' => 'error', 'message' => 'Unauthorized access.']));
}

$action = $_GET['action'] ?? 'list';

try {
    if ($action === 'list') {
        $query = "
            SELECT u.id, u.username, u.role, u.status, 
                COALESCE(a.first_name, o.first_name, c.first_name, v.first_name) as first_name,
                COALESCE(NULL, NULL, c.middle_name, v.middle_name) as middle_name,
                COALESCE(a.last_name, o.last_name, c.last_name, v.last_name) as last_name,
                COALESCE(a.email, o.email, c.email, v.email) as email,
                COALESCE(a.phone, o.phone, c.phone, v.phone) as phone,
                COALESCE(c.doc_path, v.share_doc_path) as document
            FROM users u
            LEFT JOIN admins a ON u.id = a.user_id AND u.role IN ('admin', 'super_admin')
            LEFT JOIN officers o ON u.id = o.user_id AND u.role = 'officer'
            LEFT JOIN candidates c ON u.id = c.user_id AND u.role = 'candidate'
            LEFT JOIN voters v ON u.id = v.user_id AND u.role = 'voter'
        ";
        if ($_SESSION['role'] !== 'super_admin') {
            $query .= " WHERE u.role NOT IN ('admin', 'super_admin')";
        }
        $stmt = $pdo->query($query);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $users]);
    } elseif ($action === 'toggle_status') {
        $user_id = $_POST['user_id'] ?? 0;
        $stmt = $pdo->prepare("SELECT status FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if ($user) {
            $new_status = ($user['status'] === 'active') ? 'inactive' : 'active';
            $update_stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
            $update_stmt->execute([$new_status, $user_id]);
            
            // Log action
            logAudit($pdo, $_SESSION['user_id'], $_SESSION['role'], 'Update User Status', "Changed user ID $user_id status to $new_status");
            
            echo json_encode(['status' => 'success', 'message' => 'User status updated to ' . $new_status]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User not found.']);
        }
    } elseif ($action === 'get_user') {
        $user_id = $_GET['id'] ?? 0;
        
        $stmt = $pdo->prepare("SELECT id, username, role, status FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            if ($user['role'] === 'voter') {
                $stmt = $pdo->prepare("SELECT first_name, middle_name, last_name, email, phone FROM voters WHERE user_id = ?");
            } elseif ($user['role'] === 'candidate') {
                $stmt = $pdo->prepare("SELECT first_name, middle_name, last_name, email, phone FROM candidates WHERE user_id = ?");
            } elseif ($user['role'] === 'officer') {
                $stmt = $pdo->prepare("SELECT first_name, last_name, email, phone FROM officers WHERE user_id = ?");
            } elseif ($user['role'] === 'admin' || $user['role'] === 'super_admin') {
                if ($_SESSION['role'] !== 'super_admin') {
                    die(json_encode(['status' => 'error', 'message' => 'Unauthorized to edit admins.']));
                }
                $stmt = $pdo->prepare("SELECT first_name, last_name, email, phone FROM admins WHERE user_id = ?");
            }
            if (isset($stmt)) {
                $stmt->execute([$user_id]);
                $details = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($details) {
                    $user = array_merge($user, $details);
                }
            }
            echo json_encode(['status' => 'success', 'data' => $user]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User not found.']);
        }
    } elseif ($action === 'update_user') {
        $user_id = $_POST['edit_user_id'] ?? 0;
        $username = $_POST['edit_username'] ?? '';
        $password = $_POST['edit_password'] ?? '';
        $first_name = $_POST['edit_first_name'] ?? '';
        $middle_name = $_POST['edit_middle_name'] ?? '';
        $last_name = $_POST['edit_last_name'] ?? '';
        $email = $_POST['edit_email'] ?? '';
        $phone = $_POST['edit_phone'] ?? '';
        
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
            
            if ($user) {
                $role = $user['role'];
                
                if (!empty($password)) {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
                    $stmt->execute([$username, $hashed, $user_id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
                    $stmt->execute([$username, $user_id]);
                }
                
                if ($role === 'voter') {
                    $stmt = $pdo->prepare("UPDATE voters SET first_name = ?, middle_name = ?, last_name = ?, email = ?, phone = ? WHERE user_id = ?");
                    $stmt->execute([$first_name, $middle_name, $last_name, $email, $phone, $user_id]);
                } elseif ($role === 'candidate') {
                    $stmt = $pdo->prepare("UPDATE candidates SET first_name = ?, middle_name = ?, last_name = ?, email = ?, phone = ? WHERE user_id = ?");
                    $stmt->execute([$first_name, $middle_name, $last_name, $email, $phone, $user_id]);
                } elseif ($role === 'officer') {
                    $stmt = $pdo->prepare("UPDATE officers SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE user_id = ?");
                    $stmt->execute([$first_name, $last_name, $email, $phone, $user_id]);
                } elseif ($role === 'admin' || $role === 'super_admin') {
                    if ($_SESSION['role'] !== 'super_admin') {
                        die(json_encode(['status' => 'error', 'message' => 'Unauthorized to update admins.']));
                    }
                    $stmt = $pdo->prepare("UPDATE admins SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE user_id = ?");
                    $stmt->execute([$first_name, $last_name, $email, $phone, $user_id]);
                }
                
                $pdo->commit();
                
                // Log action
                logAudit($pdo, $_SESSION['user_id'], $_SESSION['role'], 'Edit User', "Edited profile for user ID $user_id");
                
                echo json_encode(['status' => 'success', 'message' => 'User updated successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'User not found.']);
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } elseif ($action === 'delete_user') {
        $user_id = $_POST['user_id'] ?? 0;
        
        $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $target_user = $stmt->fetch();
        
        if (!$target_user) {
            die(json_encode(['status' => 'error', 'message' => 'User not found.']));
        }
        
        $target_role = $target_user['role'];
        
        if ($user_id == $_SESSION['user_id']) {
            die(json_encode(['status' => 'error', 'message' => 'You cannot delete your own account.']));
        }
        
        if ($target_role === 'super_admin') {
            die(json_encode(['status' => 'error', 'message' => 'Cannot delete Admin Boss.']));
        }
        
        if ($target_role === 'admin' && $_SESSION['role'] !== 'super_admin') {
            die(json_encode(['status' => 'error', 'message' => 'Only Admin Boss can delete admins.']));
        }
        
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        
        logAudit($pdo, $_SESSION['user_id'], $_SESSION['role'], 'Delete User', "Deleted user ID $user_id ($target_role)");
        
        echo json_encode(['status' => 'success', 'message' => 'User deleted successfully.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
