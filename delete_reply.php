<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Only admins can delete replies']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$reply_id = $_GET['id'] ?? 0;

if ($reply_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid reply ID']);
    exit();
}

// Delete the reply from the database
$stmt = $conn->prepare("DELETE FROM replies WHERE id = ?");
$stmt->bind_param("i", $reply_id);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to delete reply']);
}

$stmt->close();
$conn->close();
?>