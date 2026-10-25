<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) {
    die(json_encode(['status' => 'error', 'message' => 'Unauthorized access.']));
}

$type = $_GET['type'] ?? '';

try {
    if ($type === 'voters') {
        // Voter Participation
        $stmt = $pdo->query("SELECT 
            COUNT(*) as total_registered,
            SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) as total_approved,
            SUM(CASE WHEN has_voted = 1 THEN 1 ELSE 0 END) as total_voted
        FROM voters");
        $data = $stmt->fetch();
        echo json_encode(['status' => 'success', 'data' => $data]);
        
    } elseif ($type === 'audit') {
        // Audit Summary
        $stmt = $pdo->query("SELECT action, COUNT(*) as count FROM audit_logs GROUP BY action ORDER BY count DESC LIMIT 10");
        $data = $stmt->fetchAll();
        echo json_encode(['status' => 'success', 'data' => $data]);
        
    } elseif ($type === 'results') {
        // Election Results
        // First check if phase is Results Published
        $stmtStatus = $pdo->query("SELECT setting_value FROM system_settings WHERE setting_key = 'election_status'");
        $status = $stmtStatus->fetchColumn();
        
        if ($status !== 'Results Published') {
            echo json_encode(['status' => 'error', 'message' => 'Results are not yet published by the administrator. Current phase: ' . $status]);
            exit;
        }

        $stmt = $pdo->query("SELECT 
                c.candidate_category, c.first_name, c.middle_name, c.last_name, 
                COUNT(v.id) as total_votes
            FROM candidates c
            LEFT JOIN votes v ON c.id = v.candidate_id
            WHERE c.status = 'Final'
            GROUP BY c.id
            ORDER BY c.candidate_category ASC, total_votes DESC");
        $data = $stmt->fetchAll();
        
        foreach ($data as &$c) {
            $c['name'] = $c['first_name'] . ' ' . $c['middle_name'] . ' ' . $c['last_name'];
        }
        
        echo json_encode(['status' => 'success', 'data' => $data]);
        
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid report type.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>

