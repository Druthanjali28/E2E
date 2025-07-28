<?php
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$questionId = $_GET['id'] ?? null;
$replyUserName = $data['replyUserName'] ?? null;
$replyText = $data['replyText'] ?? null;

if (!$questionId || !$replyUserName || !$replyText) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

// Insert the reply with 'pending' status
$stmt = $pdo->prepare("INSERT INTO replies (discussion_id, reply_user_name, reply_text, status) VALUES (?, ?, ?, 'pending')");
$stmt->execute([$questionId, $replyUserName, $replyText]);

echo json_encode(['status' => 'success', 'message' => 'Reply submitted for admin approval']);
?>