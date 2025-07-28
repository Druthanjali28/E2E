<?php
require 'db.php';

$filter = $_GET['filter'] ?? 'all';

$query = "SELECT * FROM discussions";
$params = [];

if ($filter === 'today') {
    $query .= " WHERE DATE(created_at) = CURDATE()";
} elseif ($filter === 'this-week') {
    $query .= " WHERE YEARWEEK(created_at, 1) = YEARWEEK(NOW(), 1)";
} elseif ($filter === 'this-month') {
    $query .= " WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())";
} elseif ($filter === 'last-month') {
    $query .= " WHERE MONTH(created_at) = MONTH(NOW() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(NOW() - INTERVAL 1 MONTH)";
} elseif ($filter === 'this-year') {
    $query .= " WHERE YEAR(created_at) = YEAR(NOW())";
} elseif ($filter === 'last-year') {
    $query .= " WHERE YEAR(created_at) = YEAR(NOW() - INTERVAL 1 YEAR)";
}

$stmt = $pdo->prepare($query);
$stmt->execute();
$discussions = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($discussions as &$discussion) {
    // Fetch only approved replies
    $stmt = $pdo->prepare("SELECT * FROM replies WHERE discussion_id = ? AND status = 'approved'");
    $stmt->execute([$discussion['id']]);
    $discussion['replies'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode($discussions);
?>