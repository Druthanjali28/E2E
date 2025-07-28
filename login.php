<?php
session_start();
include 'db-config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query the database for the user
    $sql = "SELECT id, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id, $hashed_password);

    if ($stmt->fetch()) {
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            header("Location: index.php");
            exit();
        } else {
            header("Location: login.html?error=Invalid email or password");
            exit();
        }
    } else {
        header("Location: login.html?error=Invalid email or password");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>