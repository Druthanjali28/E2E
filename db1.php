<?php
$servername = "localhost"; // Change if using remote MySQL
$username = "root";        // Your MySQL username
$password = "";            // Your MySQL password
$database = "feedback_db"; // Your database name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}
?>
