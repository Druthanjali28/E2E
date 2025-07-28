<?php
include 'db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $contact_number = $mobile;

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['error' => 'Email already exists']);
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, mobile, email, password, contact_number) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $mobile, $email, $password, $contact_number);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to register user']);
        }
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request']);
}

$conn->close();
?>