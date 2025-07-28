<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

// Count total notes
$stmt = $conn->prepare("SELECT COUNT(*) as total_notes FROM notes WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$total_notes = $result->fetch_assoc()['total_notes'];
$stmt->close();

// Count total uploaded links (non-empty link and more_link fields)
$stmt_links = $conn->prepare("SELECT (SUM(CASE WHEN link IS NOT NULL AND link != '' THEN 1 ELSE 0 END) + SUM(CASE WHEN more_link IS NOT NULL AND more_link != '' THEN 1 ELSE 0 END)) as total_links FROM notes WHERE user_id = ?");
$stmt_links->bind_param("i", $user_id);
$stmt_links->execute();
$result_links = $stmt_links->get_result();
$total_links = $result_links->fetch_assoc()['total_links'] ?? 0;
$stmt_links->close();

// Fetch pending replies from discussion_forum database
$stmt_replies = $pdo_forum->prepare("SELECT r.*, d.topic FROM replies r JOIN discussions d ON r.discussion_id = d.id WHERE r.status = 'pending'");
$stmt_replies->execute();
$pending_replies = $stmt_replies->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ONSS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #e0e7ff, #f3f4f6);
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .sidebar {
            width: 250px;
            background: #fff;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sidebar .user-info {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar .user-info .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #1e90ff;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
            color: #fff;
        }

        .sidebar .user-info p {
            margin: 5px 0;
            font-size: 14px;
        }

        .sidebar .nav-links {
            list-style: none;
            width: 100%;
        }

        .sidebar .nav-links li {
            margin: 10px 0;
        }

        .sidebar .nav-links a {
            display: flex;
            align-items: center;
            padding: 10px;
            color: #333;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .sidebar .nav-links a:hover,
        .sidebar .nav-links a.active {
            background: #e0e7ff;
        }

        .sidebar .nav-links a i {
            margin-right: 10px;
        }

        .sidebar .footer {
            margin-top: auto;
            font-size: 12px;
            color: #666;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .header .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1e90ff;
        }

        .header .user-menu {
            position: relative;
        }

        .header .user-menu .avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #ff4d4d;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
            color: #fff;
        }

        .header .user-menu .dropdown {
            position: absolute;
            top: 40px;
            right: 0;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            display: none;
        }

        .header .user-menu:hover .dropdown {
            display: block;
        }

        .header .user-menu .dropdown a {
            display: block;
            padding: 10px;
            color: #333;
            border-bottom: 1px solid #eee;
        }

        .header .user-menu .dropdown a:last-child {
            border-bottom: none;
        }

        .cards {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            flex: 1;
            text-align: center;
        }

        .card .icon {
            width: 50px;
            height: 50px;
            background: #1e90ff;
            margin: 0 auto;
        }

        .card h3 {
            margin: 10px 0;
            font-size: 24px;
        }

        .card p {
            color: #666;
        }

        .card a {
            color: #1e90ff;
        }

        .pending-replies {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .pending-replies h2 {
            margin-bottom: 20px;
        }

        .reply-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .reply-item:last-child {
            border-bottom: none;
        }

        .reply-item p {
            margin: 5px 0;
        }

        .reply-item .actions button {
            padding: 5px 10px;
            margin-left: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .reply-item .actions .approve {
            background: #28a745;
            color: #fff;
        }

        .reply-item .actions .reject {
            background: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="user-info">
            <div class="avatar">
                <i class="fas fa-user"></i>
            </div>
            <p><?php echo htmlspecialchars($user_name); ?></p>
            <p><?php echo htmlspecialchars($user_email); ?></p>
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php" class="active"><i>🏠</i> Dashboard</a></li>
            <li><a href="add_notes.php"><i>📝</i> Add Notes</a></li>
            <li><a href="manage_notes.php"><i>📚</i> Manage Notes</a></li>
            <li><a href="profile.php"><i>👤</i> Profile</a></li>
        </ul>
        <div class="footer">
            <p>© Online Notes Sharing System</p>
        </div>
    </div>
    <div class="main-content">
        <div class="header">
            <div class="logo"># ONSS</div>
            <div class="user-menu">
                <div class="avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="dropdown">
                    <a href="profile.php">My Profile</a>
                    <a href="change_password.php">Settings</a>
                    <a href="logout.php">Log Out</a>
                </div>
            </div>
        </div>
        <h1>Hello, <?php echo htmlspecialchars($user_name); ?> Welcome to your panel</h1>
        <div class="cards">
            <div class="card">
                <div class="icon"></div>
                <h3><?php echo $total_notes; ?></h3>
                <p>Total Uploaded Subject Notes</p>
                <a href="manage_notes.php">View Detail</a>
            </div>
            <div class="card">
                <div class="icon"></div>
                <h3><?php echo $total_links; ?></h3>
                <p>Total Uploaded Notes Links</p>
                <a href="manage_notes.php">View Detail</a>
            </div>
        </div>

        <!-- Pending Replies Section -->
        <div class="pending-replies">
            <h2>Pending Replies for Approval</h2>
            <?php if (empty($pending_replies)): ?>
                <p>No pending replies to approve.</p>
            <?php else: ?>
                <?php foreach ($pending_replies as $reply): ?>
                    <div class="reply-item">
                        <div>
                            <p><strong>Topic:</strong> <?php echo htmlspecialchars($reply['topic']); ?></p>
                            <p><strong>User:</strong> <?php echo htmlspecialchars($reply['reply_user_name']); ?></p>
                            <p><strong>Reply:</strong> <?php echo htmlspecialchars($reply['reply_text']); ?></p>
                            <p><strong>Posted on:</strong> <?php echo $reply['created_at']; ?></p>
                        </div>
                        <div class="actions">
                            <button class="approve" onclick="handleReplyAction(<?php echo $reply['id']; ?>, 'approve')">Approve</button>
                            <button class="reject" onclick="handleReplyAction(<?php echo $reply['id']; ?>, 'reject')">Reject</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        async function handleReplyAction(replyId, action) {
            const response = await fetch('approve_reply.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ reply_id: replyId, action: action })
            });
            const result = await response.json();
            alert(result.message);
            if (result.status === 'success') {
                location.reload(); // Refresh the page to update the list
            }
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>