<?php
// Connection to onss database
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'onss';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Connection to discussion_forum database using PDO
$forum_db = 'discussion_forum';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$forum_db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo_forum = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Discussion Forum Database Connection Failed: " . $e->getMessage());
}
?>