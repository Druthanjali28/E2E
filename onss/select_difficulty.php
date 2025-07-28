<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get form data from add_notes.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $language = $_POST['language'] ?? '';
    $difficulty = $_POST['difficulty'] ?? '';
    $notes_description = $_POST['notesDescription'] ?? '';
    $link = $_POST['link'] ?? '';
    $more_link = $_POST['moreLink'] ?? '';

    // Store the form data in the session to pass to the next step
    $_SESSION['note_data'] = [
        'language' => $language,
        'difficulty' => $difficulty,
        'notes_description' => $notes_description,
        'link' => $link,
        'more_link' => $more_link
    ];
} else {
    // If the page is accessed directly without form submission, redirect back
    header("Location: courses1.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Difficulty - ONSS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: url('https://images.unsplash.com/photo-1600585154347-be6161a56a0c') no-repeat center center/cover;
            color: #333;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .difficulty-container {
            text-align: center;
        }

        .difficulty-container h2 {
            font-size: 36px;
            margin-bottom: 30px;
            color: #333;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .difficulty-options {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 30px;
        }

        .difficulty {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s ease, background 0.3s ease;
            width: 150px;
        }

        .difficulty:hover {
            transform: scale(1.05);
            background: rgba(255, 255, 255, 1);
        }

        .back-button {
            display: inline-block;
            padding: 12px 30px;
            background: #1e90ff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .back-button:hover {
            background: #1c86ee;
        }

        a {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>
    <div class="difficulty-container">
        <h2><?php echo htmlspecialchars($language); ?>: Select a Level</h2>
        <div class="difficulty-options">
            <a href="save_note.php?difficulty=Easy"><div class="difficulty">Easy</div></a>
            <a href="save_note.php?difficulty=Intermediate"><div class="difficulty">Intermediate</div></a>
            <a href="save_note.php?difficulty=Hard"><div class="difficulty">Hard</div></a>
        </div>
        <a href="add_notes.php?course=<?php echo urlencode($language); ?>" class="back-button">GO BACK</a>
    </div>
</body>
</html>