<?php
include 'db1.php';
header('Content-Type: application/json');

$sql = "SELECT * FROM feedback ORDER BY createdAt DESC";
$result = $conn->query($sql);

$feedbacks = [];
while ($row = $result->fetch_assoc()) {
    $feedbacks[] = $row;
}

echo json_encode($feedbacks);
$conn->close();
?>