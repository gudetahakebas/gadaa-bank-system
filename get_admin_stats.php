<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) {
    die(json_encode(['status' => 'error', 'message' => 'Unauthorized.']));
}

try {
    // 1. Total Voters
    $stmtV = $pdo->query("SELECT COUNT(*) as total FROM voters");
    $totalVoters = $stmtV->fetch()['total'];

    // 2. Total Candidates
    $stmtC = $pdo->query("SELECT COUNT(*) as total FROM candidates");
    $totalCandidates = $stmtC->fetch()['total'];

    // 3. Total Votes
    $stmtVs = $pdo->query("SELECT COUNT(*) as total FROM votes");
    $totalVotes = $stmtVs->fetch()['total'];

    // 4. Election Status
    $stmtS = $pdo->query("SELECT setting_value FROM system_settings WHERE setting_key = 'election_status'");
    $election_status = $stmtS->fetch()['setting_value'] ?? 'Registration';

    echo json_encode([
        'status' => 'success',
        'total_voters' => $totalVoters,
        'total_candidates' => $totalCandidates,
        'total_votes' => $totalVotes,
        'election_status' => $election_status
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>

