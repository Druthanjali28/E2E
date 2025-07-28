<?php
include 'db_connect.php';

header('Content-Type: application/json');

$stmt = $conn->prepare("SELECT id, name FROM users WHERE id IN (SELECT DISTINCT user_id FROM notes)");
$stmt->execute();
$result = $stmt->get_result();
$admins = [];

while ($row = $result->fetch_assoc()) {
    $admins[] = $row;
}

error_log("Fetched admins: " . json_encode($admins)); // Debugging
$stmt->close();
$conn->close();

echo json_encode($admins);
?>