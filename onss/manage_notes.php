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

// Fetch notes without the 'subject' column
$stmt = $conn->prepare("SELECT id, notes_title, notes_description, link, more_link, creation_date FROM notes WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$notes = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Notes - ONSS</title>
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
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #ff4d4d;
            margin: 0 auto;
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

        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        .table-container h2 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        table th,
        table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        table th {
            background: #f5f5f5;
            font-weight: bold;
        }

        table td a {
            color: #1e90ff;
            margin-right: 10px;
        }

        .action-buttons a {
            padding: 6px 12px;
            border-radius: 4px;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
        }

        .action-buttons .edit {
            background: #1e90ff;
            margin-right: 8px;
        }

        .action-buttons .delete {
            background: #ff4d4d;
        }

        .notes-description {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            .main-content {
                margin-left: 200px;
                width: calc(100% - 200px);
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="user-info">
            <div class="avatar"></div>
            <p><?php echo htmlspecialchars($user_name); ?></p>
            <p><?php echo htmlspecialchars($user_email); ?></p>
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php"><i>🏠</i> Dashboard</a></li>
            <li><a href="add_notes.php"><i>📝</i> Add Notes</a></li>
            <li><a href="manage_notes.php" class="active"><i>📚</i> Manage Notes</a></li>
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
                <div class="avatar"></div>
                <div class="dropdown">
                    <a href="profile.php">My Profile</a>
                    <a href="change_password.php">Settings</a>
                    <a href="logout.php">Log Out</a>
                </div>
            </div>
        </div>
        <div class="table-container">
            <h2>Manage Notes</h2>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Links</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($notes)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No notes found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($notes as $index => $note): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($note['notes_title'] ?? ''); ?></td>
                                <td class="notes-description" title="<?php echo htmlspecialchars($note['notes_description'] ?? ''); ?>">
                                    <?php echo htmlspecialchars($note['notes_description'] ?? ''); ?>
                                </td>
                                <td>
                                    <?php if (!empty($note['link'])): ?>
                                        <a href="<?php echo htmlspecialchars($note['link']); ?>" target="_blank">Link 1</a><br>
                                    <?php endif; ?>
                                    <?php if (!empty($note['more_link'])): ?>
                                        <a href="<?php echo htmlspecialchars($note['more_link']); ?>" target="_blank">Link 2</a>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($note['creation_date'] ?? ''); ?></td>
                                <td class="action-buttons">
                                    <a href="edit_notes.php?id=<?php echo $note['id']; ?>" class="edit">Edit</a>
                                    <a href="delete_notes.php?id=<?php echo $note['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this note?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>