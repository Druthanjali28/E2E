<?php
session_start();
include 'db-config.php';

// Get the ID token from the POST request
$data = json_decode(file_get_contents('php://input'), true);
$id_token = $data['credential'] ?? '';

if (empty($id_token)) {
    echo json_encode(['success' => false, 'error' => 'No credential provided']);
    exit;
}

// Verify token with Google's tokeninfo endpoint
$verification_url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($id_token);
$response = file_get_contents($verification_url);
$payload = json_decode($response, true);

if ($payload && isset($payload['sub'])) {
    $email = $payload['email'];
    $full_name = $payload['name'] ?? 'Google User';
    $google_id = $payload['sub'];

    // Check audience (client_id) to ensure token is for your app
    $client_id = "1053051987654-lb9e50kilpnef5fr72bdtm8b3r2ipiq6.apps.googleusercontent.com";
    if ($payload['aud'] !== $client_id) {
        echo json_encode(['success' => false, 'error' => 'Invalid audience']);
        exit;
    }

    // Check if the user already exists
    $sql = "SELECT id FROM users WHERE email = ? OR google_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $google_id);
    $stmt->execute();
    $stmt->bind_result($user_id);

    if ($stmt->fetch()) {
        $_SESSION['user_id'] = $user_id;
        echo json_encode(['success' => true]);
    } else {
        $sql = "INSERT INTO users (full_name, email, google_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $full_name, $email, $google_id);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to register Google user']);
        }
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid Google token']);
}

$conn->close();
?>