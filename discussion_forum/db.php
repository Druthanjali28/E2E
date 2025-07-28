<?php
// Connection to discussion_forum database
$host = 'localhost';
$db   = 'discussion_forum';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// Connection to onss database for admin checks
$onss_db = 'onss';
$dsn_onss = "mysql:host=$host;dbname=$onss_db;charset=$charset";
try {
    $pdo_onss = new PDO($dsn_onss, $user, $pass, $options);
} catch (PDOException $e) {
    die("ONSS Database Connection Failed: " . $e->getMessage());
}
?>