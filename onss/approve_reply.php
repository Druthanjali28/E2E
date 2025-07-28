<?php
require 'db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$replyId = $data['reply_id'] ?? null;
$action = $data['action'] ?? null; // 'approve' or 'reject'

if (!$replyId || !$action) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit;
}

// Update the reply status
$newStatus = ($action === 'approve') ? 'approved' : 'rejected';
$stmt = $pdo_forum->prepare("UPDATE replies SET status = ? WHERE id = ?");
$stmt->execute([$newStatus, $replyId]);

echo json_encode(['status' => 'success', 'message' => "Reply $action successfully"]);
?>