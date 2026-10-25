<?php
session_start();
require_once 'db_config.php';

// Allow public access for results if published, otherwise check session
$status = 'view-candidates'; // Default
if (isset($_GET['status'])) $status = $_GET['status'];

try {
    $voter_category = null;
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'voter') {
        $stmtV = $pdo->prepare("SELECT voter_category FROM voters WHERE voter_id_str = ?");
        $stmtV->execute([$_SESSION['username']]);
        $voter_category = $stmtV->fetchColumn();
    }

    $query = "SELECT candidate_id_str as id, candidate_category, first_name, middle_name, last_name, education, experience, age, sex, bio, photo_path as photo, doc_path as document, status FROM candidates";
    $conditions = [];

    // Validation Rule: Exclude uncategorized candidates
    $conditions[] = "candidate_category != 'Uncategorized'";

    if ($status === 'Final') {
        $conditions[] = "status = 'Final'";
    }

    if ($voter_category === 'Influential Shareholder') {
        $conditions[] = "candidate_category IN ('Influential Shareholder Candidate', 'Independent Candidate')";
    }

    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $candidates = $stmt->fetchAll();
    
    // Add full name for convenience
    foreach ($candidates as &$c) {
        $c['name'] = $c['first_name'] . ' ' . $c['middle_name'] . ' ' . $c['last_name'];
    }

    echo json_encode(['status' => 'success', 'data' => $candidates]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
