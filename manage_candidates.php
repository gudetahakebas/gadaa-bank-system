<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'officer' && $_SESSION['role'] !== 'admin')) {
    die(json_encode(['status' => 'error', 'message' => 'Unauthorized.']));
}

$action = $_GET['action'] ?? '';

try {
    if ($action === 'finalize') {
        $candidate_id_str = $_POST['candidateID'] ?? '';
        if (empty($candidate_id_str)) throw new Exception("Candidate ID missing.");

        $stmt = $pdo->prepare("UPDATE candidates SET status = 'Final' WHERE candidate_id_str = ?");
        $stmt->execute([$candidate_id_str]);
        
        echo json_encode(['status' => 'success', 'message' => 'Candidate finalized for election.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
