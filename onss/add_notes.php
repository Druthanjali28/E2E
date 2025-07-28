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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Notes - ONSS</title>
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
            text-align: center;
            color: #1e90ff;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 15px;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group button {
            width: 100%;
            padding: 12px;
            background: #1e90ff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .form-group button:hover {
            background: #1c86ee;
        }

        .form-group button:active {
            background: #1a7ed8;
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
            <li><a href="add_notes.php" class="active"><i>📝</i> Add Notes</a></li>
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
                <div class="avatar"></div>
                <div class="dropdown">
                    <a href="profile.php">My Profile</a>
                    <a href="change_password.php">Settings</a>
                    <a href="logout.php">Log Out</a>
                </div>
            </div>
        </div>
        <div class="form-container">
            <h2>Add Programming Notes</h2>
            <form id="add-notes-form">
                <div class="form-group">
                    <label for="language">Programming Language</label>
                    <select id="language" name="language" required>
                        <option value="">Select a language</option>
                        <option value="HTML">HTML</option>
                        <option value="CSS">CSS</option>
                        <option value="JavaScript">JavaScript</option>
                        <option value="Java">Java</option>
                        <option value="C">C</option>
                        <option value="PHP">PHP</option>
                        <option value="DSA">DSA</option>
                        <option value="React">React</option>
                        <option value="Next.js">Next.js</option>
                        <option value="SQL">SQL</option>
                        <option value="MongoDB">MongoDB</option>
                        <option value="Python">Python</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="difficulty">Difficulty Level</label>
                    <select id="difficulty" name="difficulty" required>
                        <option value="">Select difficulty</option>
                        <option value="Easy">Easy</option>
                        <option value="Intermediate">Intermediate</option>
                        <option value="Hard">Hard</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="notes-description">Notes Description</label>
                    <textarea id="notes-description" name="notesDescription" rows="6" required placeholder="Enter your notes here..."></textarea>
                </div>
                <div class="form-group">
                    <label for="link">Reference Link (Optional)</label>
                    <input type="url" id="link" name="link" placeholder="https://example.com">
                </div>
                <div class="form-group">
                    <label for="more-link">Additional Link (Optional)</label>
                    <input type="url" id="more-link" name="moreLink" placeholder="https://example.com">
                </div>
                <div class="form-group">
                    <label for="thumbnail">Thumbnail URL (Optional)</label>
                    <input type="url" id="thumbnail" name="thumbnail" placeholder="https://example.com/image.jpg">
                </div>
                <div class="form-group">
                    <button type="submit">Save Notes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('add-notes-form').addEventListener('submit', async (event) => {
            event.preventDefault();
            
            // Show loading state
            const submitButton = event.target.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Saving...';
            
            try {
                const formData = new FormData(event.target);
                const response = await fetch('add_notes_process.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                if (response.ok && data.success) {
                    alert('Notes saved successfully!');
                    window.location.href = 'manage_notes.php';
                } else {
                    alert(data.error || 'Failed to save notes. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Network error. Please check your connection and try again.');
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Save Notes';
            }
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>