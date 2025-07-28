<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - ONSS</title>
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
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .header {
            position: absolute;
            top: 0;
            width: 100%;
            background: #fff;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1e90ff;
        }

        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .form-container h2 {
            margin-bottom: 20px;
            text-align: center;
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

        .form-group a {
            color: #1e90ff;
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

        .form-container p {
            text-align: center;
            margin-top: 10px;
        }

        .form-container p a {
            color: #1e90ff;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo"># ONSS</div>
    </div>
    <div class="form-container">
        <h2>Sign Up</h2>
        <form id="signup-form">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="mobile">Mobile Number</label>
                <input type="text" id="mobile" name="mobile" required>
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <a href="index.php">Already Registered!!</a>
            </div>
            <div class="form-group">
                <button type="submit">SIGN UP</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('signup-form').addEventListener('submit', async (event) => {
            event.preventDefault();
            const formData = new FormData(event.target);
            try {
                const response = await fetch('signup_process.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (response.ok) {
                    window.location.href = 'index1.php';
                } else {
                    alert(data.error || 'Failed to sign up');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Server error. Please try again later.');
            }
        });
    </script>
</body>
</html>