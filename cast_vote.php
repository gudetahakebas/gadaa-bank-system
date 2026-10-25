<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'voter') {
    die(json_encode(['status' => 'error', 'message' => 'Unauthorized. Please login as a voter.']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voter_username = $_SESSION['username'];
    $candidate_id_str = $_POST['candidateID'] ?? '';

    if (empty($candidate_id_str)) {
        die(json_encode(['status' => 'error', 'message' => 'No candidate selected.']));
    }

    try {
        $pdo->beginTransaction();

        // 1. Get Voter Record and check if already voted
        $stmtV = $pdo->prepare("SELECT id, status, has_voted, voter_category FROM voters WHERE voter_id_str = ?");
        $stmtV->execute([$voter_username]);
        $voter = $stmtV->fetch();

        if (!$voter) {
            throw new Exception("Voter record not found.");
        }

        if ($voter['status'] !== 'Approved') {
            throw new Exception("Your account is not approved to vote yet.");
        }

        if ($voter['has_voted']) {
            throw new Exception("You have already cast your vote.");
        }

        // 2. Get Candidate ID
        $stmtC = $pdo->prepare("SELECT id, candidate_category FROM candidates WHERE candidate_id_str = ? AND status = 'Final'");
        $stmtC->execute([$candidate_id_str]);
        $candidate = $stmtC->fetch();

        if (!$candidate) {
            throw new Exception("Invalid or non-finalized candidate.");
        }

        if ($voter['voter_category'] === 'Influential Shareholder' && $candidate['candidate_category'] === 'Non-Influential Shareholder Candidate') {
            throw new Exception("You are not authorized to vote for this candidate category.");
        }

        // 3. Record Vote
        $stmtVote = $pdo->prepare("INSERT INTO votes (voter_id, candidate_id) VALUES (?, ?)");
        $stmtVote->execute([$voter['id'], $candidate['id']]);

        // 4. Mark Voter as voted
        $stmtMark = $pdo->prepare("UPDATE voters SET has_voted = TRUE WHERE id = ?");
        $stmtMark->execute([$voter['id']]);

        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Your vote has been recorded securely.']);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
