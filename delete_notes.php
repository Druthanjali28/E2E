<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    header("Location: manage_notes.php");
    exit();
}

$note_id = $_GET['id'];

// Delete the note
$stmt = $conn->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $note_id, $user_id);

if ($stmt->execute()) {
    header("Location: manage_notes.php");
    exit();
} else {
    // Handle error (e.g., display an error message)
    header("Location: manage_notes.php?error=Failed to delete note");
    exit();
}

$stmt->close();
$conn->close();
?>