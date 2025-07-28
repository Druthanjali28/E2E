<?php
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$userName = $data['userName'] ?? null;
$topic = $data['topic'] ?? null;
$questionText = $data['questionText'] ?? null;

if (!$userName || !$topic || !$questionText) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

// Insert the discussion into the discussions table
$stmt = $pdo->prepare("INSERT INTO discussions (user_name, topic, question_text) VALUES (?, ?, ?)");
$stmt->execute([$userName, $topic, $questionText]);

echo json_encode(['status' => 'success']);
?>