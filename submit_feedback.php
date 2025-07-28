<?php
include 'db1.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userName = $_POST['userName'] ?? '';
    $userFeedback = $_POST['userFeedback'] ?? '';
    $rating = $_POST['rating'] ?? '';

    if (empty($userName) || empty($userFeedback) || empty($rating)) {
        echo json_encode(["error" => "All fields are required"]);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO feedback (userName, userFeedback, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $userName, $userFeedback, $rating);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Feedback submitted successfully"]);
    } else {
        echo json_encode(["error" => "Database error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>