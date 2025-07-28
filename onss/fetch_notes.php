<?php
header('Content-Type: application/json');
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$admin_id = $data['admin_id'] ?? '';
$language = $data['language'] ?? '';
$difficulty = $data['difficulty'] ?? '';

if (empty($admin_id) || empty($language) || empty($difficulty)) {
    echo json_encode(['error' => 'Missing required parameters']);
    exit();
}

$stmt = $conn->prepare("SELECT notes_title, notes_description, link, more_link, thumbnail FROM notes WHERE user_id = ? AND subject = ? AND difficulty = ?");
$stmt->bind_param("iss", $admin_id, $language, $difficulty);
$stmt->execute();
$result = $stmt->get_result();
$notes = [];

while ($row = $result->fetch_assoc()) {
    $notes[] = $row;
}

if (empty($notes)) {
    echo json_encode(['error' => 'No notes found']);
} else {
    echo json_encode($notes);
}

$stmt->close();
$conn->close();
?>