<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index1.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - ONSS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f0f0f0; /* Fallback background color */
            color: #333;
            min-height: 100vh;
            padding: 20px;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80') no-repeat center center fixed;
            background-size: cover;
            opacity: 1; /* Adjust this value to change the opacity */
            z-index: 1;
        }

        a {
            text-decoration: none;
            color: #1e90ff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .header {
            background: rgba(255, 255, 255, 0.9);
            padding: 10px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
        }

        .header .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1e90ff;
        }

        h2 {
            background: rgba(255, 255, 255, 0.9);
            padding: 10px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 24px;
            color: #333;
        }

        .admins-list, .languages-list, .notes-list {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* Exactly 4 columns per row */
            gap: 20px;
            margin-bottom: 20px;
        }

        .admin, .language, .note {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.3s ease, background 0.3s ease;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            width: 100%; /* Ensure full width within the grid cell */
            height: 100px; /* Fixed height for uniformity */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }

        .admin:hover, .language:hover {
            transform: scale(1.05);
            background: rgba(224, 231, 255, 0.9);
        }

        .difficulty-options {
            display: grid; /* Changed from flex to grid to match the layout of courses */
            grid-template-columns: repeat(3, 1fr); /* 3 columns for Easy, Intermediate, Hard */
            gap: 20px; /* Match the gap of the courses grid */
            margin-bottom: 20px;
            max-width: 900px; /* Adjust to control the overall width */
            margin-left: auto;
            margin-right: auto;
        }

        .difficulty {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.3s ease, background 0.3s ease;
            text-align: center;
            font-size: 16px; /* Match the font size of the course buttons */
            font-weight: bold;
            width: 100%; /* Ensure full width within the grid cell */
            height: 100px; /* Match the height of the course buttons */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }

        .difficulty:hover {
            transform: scale(1.05);
            background: rgba(224, 231, 255, 0.9);
        }

        .note {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
            cursor: default;
            width: 100%; /* Ensure full width within the grid cell */
            height: 350px; /* Fixed height for uniformity */
            display: flex;
            flex-direction: column;
        }

        .note:hover {
            transform: scale(1.02);
        }

        .note a {
            display: block;
            width: 100%;
            height: 150px;
        }

        .note img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }

        .note-content {
            padding: 15px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .note-content h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }

        .note-content p {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-grow: 1;
        }

        .note-content .clicks {
            font-size: 12px;
            color: #999;
            margin-top: 10px;
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
            margin-top: 20px;
            text-align: center;
        }

        .back-button:hover {
            background: #1c86ee;
        }

        #back-button-container {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo"># ONSS</div>
    </div>
    <div class="container">
        <h2 id="admins-heading">Admins</h2>
        <div class="admins-list" id="admins-list"></div>

        <h2 id="languages-heading" style="display: none;">Courses Grid</h2>
        <div class="languages-list" id="languages-list" style="display: none;"></div>

        <h2 id="difficulty-heading" style="display: none;">Select a Level</h2>
        <div class="difficulty-options" id="difficulty-options" style="display: none;">
            <div class="difficulty" data-difficulty="Easy">Easy</div>
            <div class="difficulty" data-difficulty="Intermediate">Intermediate</div>
            <div class="difficulty" data-difficulty="Hard">Hard</div>
        </div>

        <h2 id="notes-heading" style="display: none;">Notes</h2>
        <div class="notes-list" id="notes-list" style="display: none;"></div>

        <div id="back-button-container" style="text-align: center; display: none;">
            <a href="#" class="back-button" id="back-button">GO BACK</a>
        </div>
    </div>

    <script>
        const adminsHeading = document.getElementById('admins-heading');
        const adminsList = document.getElementById('admins-list');
        const languagesHeading = document.getElementById('languages-heading');
        const languagesList = document.getElementById('languages-list');
        const difficultyHeading = document.getElementById('difficulty-heading');
        const difficultyOptions = document.getElementById('difficulty-options');
        const notesHeading = document.getElementById('notes-heading');
        const notesList = document.getElementById('notes-list');
        const backButtonContainer = document.getElementById('back-button-container');
        const backButton = document.getElementById('back-button');

        // Static list of 12 languages
        const staticLanguages = [
            "HTML", "CSS", "JavaScript", "Java", "C",
            "PHP", "DSA", "React", "Next.js", "SQL",
            "MongoDB", "Python"
        ];

        // Placeholder image for notes (used when thumbnail is not provided)
        const placeholderImage = 'https://via.placeholder.com/250x150.png?text=Course+Thumbnail';

        // Step 1: Fetch and display admins
        async function loadAdmins() {
            try {
                const response = await fetch('fetch_admins.php');
                const admins = await response.json();
                console.log('Admins:', admins);
                adminsList.innerHTML = '';
                if (admins.length === 0) {
                    adminsList.innerHTML = '<p>No admins found.</p>';
                    return;
                }
                admins.forEach(admin => {
                    const adminDiv = document.createElement('div');
                    adminDiv.classList.add('admin');
                    adminDiv.textContent = admin.name;
                    adminDiv.dataset.id = admin.id;
                    adminDiv.addEventListener('click', () => showLanguages(admin.id));
                    adminsList.appendChild(adminDiv);
                });
            } catch (error) {
                console.error('Error fetching admins:', error);
                adminsList.innerHTML = '<p>Error loading admins. Please try again later.</p>';
            }
        }

        // Step 2: Show static 12 languages for the selected admin
        function showLanguages(adminId) {
            adminsHeading.style.display = 'none';
            adminsList.style.display = 'none';
            languagesHeading.style.display = 'block';
            languagesList.style.display = 'grid';
            backButtonContainer.style.display = 'block';

            languagesList.innerHTML = '';
            staticLanguages.forEach(language => {
                const languageDiv = document.createElement('div');
                languageDiv.classList.add('language');
                languageDiv.textContent = language;
                languageDiv.addEventListener('click', () => showDifficulty(adminId, language));
                languagesList.appendChild(languageDiv);
            });

            backButton.onclick = resetToAdmins;
        }

        // Step 3: Show difficulty options for the selected language
        function showDifficulty(adminId, language) {
            languagesHeading.style.display = 'none';
            languagesList.style.display = 'none';
            difficultyHeading.style.display = 'block';
            difficultyHeading.textContent = `${language}: Select a Level`;
            difficultyOptions.style.display = 'grid';
            backButtonContainer.style.display = 'block';

            backButton.onclick = () => {
                languagesHeading.style.display = 'block';
                languagesList.style.display = 'grid';
                difficultyHeading.style.display = 'none';
                difficultyOptions.style.display = 'none';
                notesHeading.style.display = 'none';
                notesList.style.display = 'none';
            };

            const difficulties = document.querySelectorAll('.difficulty');
            difficulties.forEach(difficulty => {
                difficulty.onclick = null; // Remove old listeners
                difficulty.addEventListener('click', () => {
                    console.log('Clicked Difficulty - Admin ID:', adminId, 'Language:', language, 'Difficulty:', difficulty.dataset.difficulty);
                    showNotes(adminId, language, difficulty.dataset.difficulty);
                });
            });
        }

        // Step 4: Fetch and display notes with clickable thumbnails
        async function showNotes(adminId, language, difficulty) {
            difficultyHeading.style.display = 'none';
            difficultyOptions.style.display = 'none';
            notesHeading.style.display = 'block';
            notesList.style.display = 'grid';
            backButtonContainer.style.display = 'block';

            console.log('Fetching notes - Admin ID:', adminId, 'Language:', language, 'Difficulty:', difficulty);

            try {
                const response = await fetch('fetch_notes.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ admin_id: adminId, language: language, difficulty: difficulty })
                });
                const data = await response.json();
                console.log('Fetched Data:', data);

                notesList.innerHTML = '';
                if (data.error) {
                    notesList.innerHTML = `<p>${data.error}</p>`;
                } else if (data.length > 0) {
                    data.forEach(note => {
                        const thumbnailUrl = note.thumbnail && note.thumbnail.trim() !== '' ? note.thumbnail : placeholderImage;
                        const linkUrl = note.link && note.link.trim() !== '' ? note.link : '#';

                        const noteDiv = document.createElement('div');
                        noteDiv.classList.add('note');
                        noteDiv.innerHTML = `
                            <a href="${linkUrl}" target="_blank">
                                <img src="${thumbnailUrl}" alt="Course Thumbnail" onerror="this.src='${placeholderImage}'">
                            </a>
                            <div class="note-content">
                                <h3>${language} - ${difficulty}</h3>
                                <p>${note.notes_description}</p>
                                <span class="clicks">Clicks: 0</span>
                            </div>
                        `;
                        notesList.appendChild(noteDiv);
                    });
                } else {
                    notesList.innerHTML = '<p>No notes available for this selection.</p>';
                }
            } catch (error) {
                console.error('Error fetching notes:', error);
                notesList.innerHTML = '<p>Error loading notes. Please try again later.</p>';
            }

            backButton.onclick = () => {
                difficultyHeading.style.display = 'block';
                difficultyOptions.style.display = 'grid';
                notesHeading.style.display = 'none';
                notesList.style.display = 'none';
            };
        }

        // Reset to admins list
        function resetToAdmins() {
            adminsHeading.style.display = 'block';
            adminsList.style.display = 'grid';
            languagesHeading.style.display = 'none';
            languagesList.style.display = 'none';
            difficultyHeading.style.display = 'none';
            difficultyOptions.style.display = 'none';
            notesHeading.style.display = 'none';
            notesList.style.display = 'none';
            backButtonContainer.style.display = 'none';
        }

        // Load admins on page load
        loadAdmins();
    </script>
</body>
</html>
<?php $conn->close(); ?>