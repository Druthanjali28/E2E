<?php
// Start the session
session_start();

// Include database configuration
include 'db-config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); // Changed to login.html
    exit;
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT full_name, email, phone_number, dob, gender FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($full_name, $email, $phone_number, $dob, $gender);
$stmt->fetch();

// Check if user data is fetched
if (empty($full_name) || empty($email)) {
    // If user data isn't found, unset session and redirect to login
    unset($_SESSION['user_id']);
    header("Location: login.html?error=User data not found");
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LearnSphere</title>
  <style>
    /* Global Styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Arial', sans-serif;
    }

    body {
      background-color: #f8f9fa;
      color: #333;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    ul {
      list-style: none;
    }

    /* Navbar Styles */
    .navbar {
      background: linear-gradient(90deg, #06BBCC, #08A0B2);
      padding: 15px 0;
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 20px;
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
      color: white;
      text-transform: uppercase;
      letter-spacing: 1.5px;
    }

    .nav-links {
      display: flex;
      align-items: center;
    }

    .nav-links li {
      margin: 0 10px;
    }

    .nav-links a {
      padding: 8px 12px;
      color: white;
      font-weight: 500;
      border-radius: 5px;
      transition: background 0.3s ease;
    }

    .nav-links a:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    /* Profile Logo and Dropdown */
    .profile-container {
      position: relative;
      display: flex;
      align-items: center;
    }

    .profile-logo {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      cursor: pointer;
      background-color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 20px;
      color: #06BBCC;
      font-weight: bold;
    }

    .profile-dropdown {
      position: absolute;
      top: 50px;
      right: 0;
      background-color: white;
      border-radius: 5px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      width: 250px;
      padding: 15px;
      display: none;
      z-index: 1000;
    }

    .profile-dropdown.active {
      display: block;
    }

    .profile-info {
      margin-bottom: 15px;
    }

    .profile-info p {
      margin: 5px 0;
      font-size: 14px;
    }

    .profile-info input, .profile-info select {
      width: 100%;
      padding: 5px;
      margin: 5px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      display: none;
    }

    .profile-info button {
      padding: 5px 10px;
      background-color: #06BBCC;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin: 5px 0;
      display: block;
    }

    .profile-info button:hover {
      background-color: #0599A8;
    }

    /* Hero Section Styles */
    .hero {
      background: url('images/extra/b4.jpg') no-repeat center center/cover;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: white;
      padding: 20px;
    }

    .hero-content h3 {
      font-size: 20px;
      font-weight: 600;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: #00c6ff;
      margin-bottom: 10px;
    }

    .hero-content h1 {
      font-size: 48px;
      font-weight: bold;
      margin-bottom: 20px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .hero-content p {
      font-size: 18px;
      margin-bottom: 30px;
      line-height: 1.6;
      text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);
    }

    .hero-buttons {
      display: flex;
      gap: 20px;
    }

    .btn {
      padding: 12px 24px;
      font-size: 16px;
      font-weight: bold;
      text-transform: uppercase;
      border-radius: 5px;
      transition: all 0.3s ease;
    }

    .btn-primary {
      background: #007bff;
      color: white;
    }

    .btn-primary:hover {
      background: #0056b3;
    }

    .btn-secondary {
      background: #00c6ff;
      color: white;
    }

    .btn-secondary:hover {
      background: #007bff;
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="navbar">
    <div class="container">
      <div class="logo">LearnSphere 🌟</div>
      <nav>
        <ul class="nav-links">
          <li><a href="index.php">Home</a></li>
          <li><a href="about.html">About</a></li>
          <li><a href="courses.html">Courses</a></li>
          <li><a href="contact.html">Contact</a></li>
          <li><a href="discussion_forum/index.html">Discussion</a></li>
          <li><a href="feedback.html">Feedback</a></li>
          <li><a href="quiz.html">Quiz</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
      <div class="profile-container">
        <div class="profile-logo" id="profileLogo"><?php echo strtoupper(substr($full_name, 0, 1)); ?></div>
        <div class="profile-dropdown" id="profileDropdown">
          <div class="profile-info">
            <p><strong>Name:</strong> <span id="displayName"><?php echo htmlspecialchars($full_name); ?></span></p>
            <input type="text" id="editName" value="<?php echo htmlspecialchars($full_name); ?>">
            <p><strong>Email:</strong> <span id="displayEmail"><?php echo htmlspecialchars($email); ?></span></p>
            <input type="email" id="editEmail" value="<?php echo htmlspecialchars($email); ?>">
            <p><strong>Phone Number:</strong> <span id="displayPhone"><?php echo htmlspecialchars($phone_number ?? 'Not set'); ?></span></p>
            <input type="text" id="editPhone" value="<?php echo htmlspecialchars($phone_number ?? ''); ?>">
            <p><strong>Date of Birth:</strong> <span id="displayDOB"><?php echo htmlspecialchars($dob ?? 'Not set'); ?></span></p>
            <input type="date" id="editDOB" value="<?php echo htmlspecialchars($dob ?? ''); ?>">
            <p><strong>Gender:</strong> <span id="displayGender"><?php echo htmlspecialchars($gender ?? 'Not set'); ?></span></p>
            <select id="editGender">
              <option value="Male" <?php echo $gender === 'Male' ? 'selected' : ''; ?>>Male</option>
              <option value="Female" <?php echo $gender === 'Female' ? 'selected' : ''; ?>>Female</option>
              <option value="Other" <?php echo $gender === 'Other' ? 'selected' : ''; ?>>Other</option>
            </select>
            <button id="editBtn">Edit Profile</button>
            <button id="saveBtn" style="display: none;">Save</button>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h3>BEST ONLINE COURSES</h3>
      <h1>The Best Online Learning Platform</h1>
      <p>Online education has transformed the way people acquire knowledge, making learning more accessible, flexible, and convenient.</p>
      <div class="hero-buttons">
        <a href="#" class="btn btn-secondary">Join Now</a>
      </div>
    </div>
  </section>

  <script>
    // Array of background image URLs
    const backgroundImages = [
      'home.jpg',
      'images/extra/b1.jpg',
      'images/extra/b4.jpg',
      'images/extra/b3.jpg',
      'images/extra/b4.jpg'
    ];
    let currentImageIndex = 0;

    // Function to change background image
    function changeBackgroundImage() {
      const heroSection = document.querySelector('.hero');
      currentImageIndex = (currentImageIndex + 1) % backgroundImages.length;  // Loop through images
      heroSection.style.backgroundImage = `url('${backgroundImages[currentImageIndex]}')`;
    }

    // Change background every 2 seconds
    setInterval(changeBackgroundImage, 2000);

    // Profile dropdown toggle
    const profileLogo = document.getElementById('profileLogo');
    const profileDropdown = document.getElementById('profileDropdown');
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const displayName = document.getElementById('displayName');
    const editName = document.getElementById('editName');
    const displayEmail = document.getElementById('displayEmail');
    const editEmail = document.getElementById('editEmail');
    const displayPhone = document.getElementById('displayPhone');
    const editPhone = document.getElementById('editPhone');
    const displayDOB = document.getElementById('displayDOB');
    const editDOB = document.getElementById('editDOB');
    const displayGender = document.getElementById('displayGender');
    const editGender = document.getElementById('editGender');

    profileLogo.addEventListener('click', () => {
      profileDropdown.classList.toggle('active');
    });

    // Edit/Save functionality
    editBtn.addEventListener('click', () => {
      // Show input fields, hide display text
      displayName.style.display = 'none';
      editName.style.display = 'block';
      displayEmail.style.display = 'none';
      editEmail.style.display = 'block';
      displayPhone.style.display = 'none';
      editPhone.style.display = 'block';
      displayDOB.style.display = 'none';
      editDOB.style.display = 'block';
      displayGender.style.display = 'none';
      editGender.style.display = 'block';
      editBtn.style.display = 'none';
      saveBtn.style.display = 'block';
    });

    saveBtn.addEventListener('click', () => {
      // Update display with new values
      displayName.textContent = editName.value;
      displayEmail.textContent = editEmail.value;
      displayPhone.textContent = editPhone.value;
      displayDOB.textContent = editDOB.value;
      displayGender.textContent = editGender.value;

      // Hide input fields, show display text
      displayName.style.display = 'block';
      editName.style.display = 'none';
      displayEmail.style.display = 'block';
      editEmail.style.display = 'none';
      displayPhone.style.display = 'block';
      editPhone.style.display = 'none';
      displayDOB.style.display = 'block';
      editDOB.style.display = 'none';
      displayGender.style.display = 'block';
      editGender.style.display = 'none';
      editBtn.style.display = 'block';
      saveBtn.style.display = 'none';

      // Send updated data to the server
      fetch('update_profile.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          full_name: editName.value,
          email: editEmail.value,
          phone_number: editPhone.value,
          dob: editDOB.value,
          gender: editGender.value,
        }),
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Profile updated successfully!');
        } else {
          alert('Error updating profile: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the profile.');
      });
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
      if (!profileLogo.contains(e.target) && !profileDropdown.contains(e.target)) {
        profileDropdown.classList.remove('active');
      }
    });
  </script>
</body>
</html>