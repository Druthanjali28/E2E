<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, GET");

include 'db.php';

// Fetch Discussions (With Filtering)
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $filter = isset($_GET["filter"]) ? $_GET["filter"] : "all";
    $dateCondition = "";

    switch ($filter) {
        case "today":
            $dateCondition = "WHERE DATE(date) = CURDATE()";
            break;
        case "yesterday":
            $dateCondition = "WHERE DATE(date) = CURDATE() - INTERVAL 1 DAY";
            break;
        case "this-week":
            $dateCondition = "WHERE YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)";
            break;
        case "last-week":
            $dateCondition = "WHERE YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1) - 1";
            break;
        case "this-month":
            $dateCondition = "WHERE MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())";
            break;
        case "last-month":
            $dateCondition = "WHERE MONTH(date) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(date) = YEAR(CURDATE() - INTERVAL 1 MONTH)";
            break;
        case "all":
        default:
            $dateCondition = "";
    }

    $query = "SELECT * FROM discussions $dateCondition ORDER BY date DESC";
    $result = $conn->query($query);

    $discussions = [];
    while ($row = $result->fetch_assoc()) {
        $discussion_id = $row["id"];
        $replyQuery = "SELECT * FROM replies WHERE discussion_id = $discussion_id ORDER BY date ASC";
        $replyResult = $conn->query($replyQuery);
        $replies = [];
        while ($reply = $replyResult->fetch_assoc()) {
            $replies[] = $reply;
        }
        $row["replies"] = $replies;
        $discussions[] = $row;
    }

    echo json_encode($discussions);
}

// Add a Discussion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["userName"])) {
    $userName = $_POST["userName"];
    $topic = $_POST["topic"];
    $questionText = $_POST["questionText"];

    $query = "INSERT INTO discussions (user_name, topic, question_text) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $userName, $topic, $questionText);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Discussion added successfully"]);
    } else {
        echo json_encode(["error" => "Failed to add discussion"]);
    }
}

// Add a Reply
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["discussionId"])) {
    $discussionId = $_POST["discussionId"];
    $replyUserName = $_POST["replyUserName"];
    $replyText = $_POST["replyText"];

    $query = "INSERT INTO replies (discussion_id, reply_user_name, reply_text) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $discussionId, $replyUserName, $replyText);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Reply added successfully"]);
    } else {
        echo json_encode(["error" => "Failed to add reply"]);
    }
}

$conn->close();
?>
