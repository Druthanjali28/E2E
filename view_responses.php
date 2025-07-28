<?php
include 'db1.php';

$sql = "SELECT userName, userFeedback, rating FROM feedback ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Responses</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #E67E22;
        }
        .feedback-box {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .rating {
            color: #f39c12;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Feedback Responses</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="feedback-box">
                    <strong><?php echo htmlspecialchars($row["userName"]); ?></strong><br>
                    <p><?php echo htmlspecialchars($row["userFeedback"]); ?></p>
                    <span class="rating">⭐ <?php echo $row["rating"]; ?>/5</span>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No feedback available.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>
