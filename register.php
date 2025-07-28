<?php
session_start();
include 'db-config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['full_name']) && !empty($_POST['email']) && !empty($_POST['phone_number']) && !empty($_POST['dob']) && !empty($_POST['gender']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])) {
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password === $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (full_name, email, phone_number, dob, gender, password) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $full_name, $email, $phone_number, $dob, $gender, $hashed_password);

            if ($stmt->execute()) {
                header("Location: login.html?success=Registration successful! Please log in.");
                exit();
            } else {
                header("Location: login.html?error=Registration failed: " . urlencode($stmt->error));
                exit();
            }
            $stmt->close();
        } else {
            header("Location: login.html?error=Passwords do not match");
            exit();
        }
    } else {
        header("Location: login.html?error=All fields are required");
        exit();
    }
    $conn->close();
}
?>