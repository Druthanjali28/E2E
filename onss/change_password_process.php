<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['currentPassword'];
    $new_password = $_POST['newPassword'];
    $confirm_password = $_POST['confirmPassword'];

    if ($new_password !== $confirm_password) {
        echo json_encode(['error' => 'New password and confirm password do not match']);
        exit();
    }

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!password_verify($current_password, $user['password'])) {
        echo json_encode(['error' => 'Current password is incorrect']);
        exit();
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashed_password, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to change password']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request']);
}

$conn->close();
?>