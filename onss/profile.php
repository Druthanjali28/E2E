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

// Fetch user details
$stmt = $conn->prepare("SELECT name, email, contact_number, registration_date FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - ONSS</title>
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

        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 0 auto;
        }

        .form-container h2 {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group button {
            width: 100%;
            padding: 10px;
            background: #1e90ff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group button:hover {
            background: #1c86ee;
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
            <li><a href="manage_notes.php"><i>📚</i> Manage Notes</a></li>
            <li><a href="profile.php" class="active"><i>👤</i> Profile</a></li>
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
        <div class="form-container">
            <h2>User Profile</h2>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" value="<?php echo htmlspecialchars($user['name']); ?>" readonly>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
            </div>
            <div class="form-group">
                <label>Contact Number</label>
                <input type="text" value="<?php echo htmlspecialchars($user['contact_number']); ?>" readonly>
            </div>
            <div class="form-group">
                <label>Registration Date</label>
                <input type="text" value="<?php echo htmlspecialchars($user['registration_date']); ?>" readonly>
            </div>
            <div class="form-group">
                <button>Update</button>
            </div>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>