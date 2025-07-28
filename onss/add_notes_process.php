<?php
header('Content-Type: application/json');
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit();
}

// Get form data
$subject = $_POST['language'] ?? '';
$difficulty = $_POST['difficulty'] ?? '';
$notes_description = $_POST['notesDescription'] ?? '';
$link = $_POST['link'] ?? '';
$more_link = $_POST['moreLink'] ?? '';
$thumbnail = $_POST['thumbnail'] ?? '';
$notes_title = $subject . ' - ' . $difficulty;

// Validate required fields
if (empty($subject) || empty($difficulty) || empty($notes_description) || empty($notes_title)) {
    echo json_encode(['error' => 'All required fields must be filled']);
    exit();
}

// Prepare and execute the SQL query
$stmt = $conn->prepare("INSERT INTO notes (user_id, subject, difficulty, notes_title, notes_description, link, more_link, thumbnail, creation_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("isssssss", $user_id, $subject, $difficulty, $notes_title, $notes_description, $link, $more_link, $thumbnail);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to save note: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>