<?php
session_start();
include 'db-config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);
$full_name = $data['full_name'] ?? '';
$email = $data['email'] ?? '';
$phone_number = $data['phone_number'] ?? '';
$dob = $data['dob'] ?? '';
$gender = $data['gender'] ?? '';
$user_id = $_SESSION['user_id'];

// Validate input
if (empty($full_name) || empty($email) || empty($phone_number) || empty($dob) || empty($gender)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

// Update the user in the database
$sql = "UPDATE users SET full_name = ?, email = ?, phone_number = ?, dob = ?, gender = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $full_name, $email, $phone_number, $dob, $gender, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating profile']);
}

$stmt->close();
$conn->close();
?>