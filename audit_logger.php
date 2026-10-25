<?php
function logAudit($pdo, $user_id, $role, $action, $details = '') {
    try {
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $stmt = $pdo->prepare("INSERT INTO audit_logs (user_id, role, action, details, ip_address) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $role, $action, $details, $ip_address]);
    } catch (PDOException $e) {
        // Silently fail or log to file if needed so it doesn't break main flow
        error_log("Audit Log Error: " . $e->getMessage());
    }
}
?>
