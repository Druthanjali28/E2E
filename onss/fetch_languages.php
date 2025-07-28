<?php
include 'db_connect.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$admin_id = $data['admin_id'] ?? '';

if (empty($admin_id)) {
    echo json_encode([]);
    exit();
}

$stmt = $conn->prepare("SELECT DISTINCT language FROM notes WHERE user_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$languages = [];

while ($row = $result->fetch_assoc()) {
    $languages[] = $row['language'];
}

$stmt->close();
$conn->close();

echo json_encode($languages);
?>