<?php
// Start the session
session_start();

// Check if the student is logged in
if (!isset($_SESSION['university_number'])) {
    header("Location: index.php");
    exit();
}

// Get the exam title from the query parameter
if (isset($_GET['exam_title'])) {
    $exam_title = htmlspecialchars($_GET['exam_title']); // Sanitize the input
    $_SESSION['exam_title'] = $exam_title; // Store it in the session for later use
    header("Location: quiz.php");
    exit(); // Prevent further script execution
}

// Retrieve university number from session
$university_number = $_SESSION['university_number'];

// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$dbname = "university_exam";

// Create connection
$connection = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Fetch the student's name from the database
$query = "SELECT student_name FROM students WHERE university_number = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $university_number);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();  // Fetch the student's name
$stmt->close();

// Prepare SQL query to fetch quizzes assigned to the student
$query = "
    SELECT se.exam_id, se.exam_title, se.exam_duration, se.department, se.exam_tips
    FROM students s
    JOIN student_exam se ON s.student_department = se.department
    WHERE s.university_number = ?
";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $university_number);
$stmt->execute();
$result = $stmt->get_result();

// Generate quiz cards dynamically
$exam_details = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $exam_id = htmlspecialchars($row['exam_id']);
        $exam_title = htmlspecialchars($row['exam_title']);
        $exam_duration = htmlspecialchars($row['exam_duration']);
        $department = htmlspecialchars($row['department']);
        $exam_tips = htmlspecialchars($row['exam_tips']);

        // Store the exam duration in the session
        $_SESSION['exam_duration'] = $exam_duration;

        $exam_details .= "
            <div class='quiz-card' data-exam-id='$exam_id' data-exam-title='$exam_title' data-exam-duration='$exam_duration'>
                <h2>$exam_title</h2>
                <p><strong>Duration:</strong> $exam_duration minutes</p>
                <p><strong>Department:</strong> $department</p>
                <button class='start-quiz-btn'>Start Exam</button>
            </div>
        ";
    }
} else {
    $exam_details = "<p>No exams available.</p>";
}


$stmt->close();
$connection->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Dashboard</title>
    <link rel="stylesheet" href="css/exam_entry.css">
    <link rel="stylesheet" href="css/exam_dashboard.css">
    <style>
        .quiz-section {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        #exam_tips{}
        .quiz-card {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            background-color: #f9f9f9;
            width: 300px;
            text-align: center;
        }
        .quiz-card h2 {
            margin-top: 0;
            color: #333;
        }
        .start-quiz-btn {
            background-color: #50B4C8;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .start-quiz-btn:hover {
            background-color: #27aac4;
        }
        /* Container for the overlay content */
.overlay-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-align: center;
    overflow: hidden;
}

/* Exam tips styling */
#overlay-tips {
    font-family: 'Arial', sans-serif;
    font-size: 1rem;
    line-height: 1.6;
    color: #444; /* Neutral gray for readability */
    background-color: #f5faff; /* Light blue for calmness */
    border-left: 5px solid #50B4C8; /* Highlight with border */
    padding: 15px 20px;
    border-radius: 8px;
    margin: 15px 0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Hover effect */
#overlay-tips:hover {
    transform: scale(1.02);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}
/* Style for the exam tips section */
#exam_tips {
    font-family: 'Arial', sans-serif; /* Ensure a clean, easy-to-read font */
    font-size: 1rem; /* Adjust size for readability */
    line-height: 1.6; /* Space out the lines for better readability */
    color: #333; /* Use a dark color for text */
    background-color: #e7f7f9; /* Subtle light background */
    padding: 12px 20px;
    border-left: 5px solid #27aac4; /* Matching color for emphasis */
    border-radius: 8px;
    margin-top: 10px;
    text-align: left; /* Align text to the left for clarity */
    white-space: pre-line; /* Preserve line breaks from PHP content */
}

/* Additional styles to clean up ugly formatting */
#exam_tips p {
    margin: 0; /* Remove extra space between paragraphs */
    padding: 0; /* Remove unnecessary padding */
}

#exam_tips strong {
    color: #27aac4; /* Blue for emphasis */
    font-weight: bold;
}

#exam_tips a {
    color: #50B4C8; /* Color for links */
    text-decoration: none; /* Remove underline */
}

#exam_tips a:hover {
    text-decoration: underline; /* Underline on hover */
}

/* Handle paragraphs that come from PHP */
#exam_tips p + p {
    margin-top: 10px; /* Add margin between paragraphs if multiple are output */
}


/* Style for the dynamic exam tips (<?= $exam_tips ?>) */
#exam_tips {
    font-size: 1rem;
    color: #333;
    background-color: #e7f7f9; /* Subtle light background for focus */
    padding: 12px;
    border-left: 5px solid #27aac4; /* Matching color for the border */
    border-radius: 8px;
    margin-top: 10px;
}

/* Emphasis on important tips */
#exam_tips strong {
    color: #27aac4; /* Blue for emphasis */
    font-weight: bold;
}

/* Buttons styling */
.btn {
    padding: 12px 25px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    margin-top: 15px;
}

.btn-confirm {
    background-color: #50B4C8;
    color: white;
    border: none;
}

.btn-confirm:hover {
    background-color: #27aac4;
}

.btn-cancel {
    background-color: #f44336;
    color: white;
    border: none;
}

.btn-cancel:hover {
    background-color: #d32f2f;
}

/* Responsive adjustments for smaller screens */
@media (max-width: 600px) {
    #overlay-tips, #exam_tips {
        font-size: 0.9rem;
        padding: 10px 15px;
    }

    .overlay-content {
        width: 90%;
        padding: 15px;
    }
}

        /* Overlay styles */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }
        .overlay-content {
            position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    width: 102%;
    padding: 20%;
    max-width: 700px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-align: center;
}
        
        .overlay-content h2 {
            margin-top: 0;
        }
        .overlay-content p {
            margin: 10px 0;
        }
        .overlay-content .btn {
            margin: 10px 5px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-confirm {
            background-color: #50B4C8;
            color: white;
        }
        .btn-confirm:hover {
            background-color: #27aac4;
        }
        .btn-cancel {
            background-color: #f44336;
            color: white;
        }
        .btn-cancel:hover {
            background-color: #d32f2f;
        }
    </style>
        <link rel="stylesheet" href="css/loader.css">
</head>
<body>
    
<div class="loader">
  <div class="load-inner load-one"></div>
  <div class="load-inner load-two"></div>
  <div class="load-inner load-three"></div>
  <span class="loader-text">Loading</span>
</div>
    <div class="quiz-dashboard">
        <aside class="sidebar">
            <div class="header">Quiz Time</div>
            <ul>
                <li class="active">Dashboard</li>
            
            </ul>
            <div class="logout" id="logout-button">
                <div><img class="icon logout1" src="picture/logout.png" width="20px" height="20px"></div>
                <a href="logout.php">Log Out</a>
            </div>
        </aside>
        <main>
            <div class="top-bar">
                <div class="user-profile">
                    <span class="profile-icon">Y </span>
                    <span class="username"><?= " ". htmlspecialchars($student_name); ?></span>
                </div>
            </div>
            <div class="quiz-section">
                <?= $exam_details; ?>
            </div>
        </main>
    </div>

    <div class="overlay" id="overlay">
    <div class="overlay-content">
        <h2 id="overlay-title">Exam Title</h2>
        <p id="overlay-tips">These are the exam tips.</p>
        <p id="exam_tips"><strong>Tips:</strong></br> <?= $exam_tips; ?></p>
        <button class="btn btn-confirm" id="confirm-btn">Start Exam</button>
        <button class="btn btn-cancel" id="cancel-btn">Cancel</button>
    </div>
</div>
<script>
document.querySelectorAll('.start-quiz-btn').forEach(button => {
    button.addEventListener('click', function () {
        const quizCard = this.parentElement;

        // Fetch exam details
        const examId = quizCard.getAttribute('data-exam-id');
        const examTitle = quizCard.getAttribute('data-exam-title');
        const examDuration = quizCard.getAttribute('data-exam-duration'); // Fetch from the card's data attribute
        
        if (!examId || !examTitle || !examDuration) {
            console.error('Exam details are missing.');
            return;
        }

        // Populate overlay
        document.getElementById('overlay-title').textContent = examTitle;
        document.getElementById('overlay-tips').textContent = 'These are the exam tips.'; // Adjust as needed

        // Show overlay
        document.getElementById('overlay').style.display = 'block';

        document.getElementById('confirm-btn').onclick = function () {
    // Create a form dynamically
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'quiz.php'; // The target PHP file

    // Add hidden inputs for the data
    const data = {
        exam_id: examId,
        exam_title: examTitle,
        university_number: '<?php echo $_SESSION['university_number']; ?>',
        exam_duration: examDuration
    };

    for (const key in data) {
        if (data.hasOwnProperty(key)) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = data[key];
            form.appendChild(input);
        }
    }

    // Append the form to the body and submit it
    document.body.appendChild(form);
    form.submit();
    // Start the timer after form submission
    startTimer(examDuration); // examDuration is in minutes
};

    });
});
// Function to start the timer
function startTimer(duration) {
    let timer = duration * 60; // Convert minutes to seconds
    const timerElement = document.getElementById('timer');

    // Update the timer every second
    const interval = setInterval(() => {
        const minutes = Math.floor(timer / 60);
        const seconds = timer % 60;

        // Display the time in MM:SS format
        timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

        // If the timer reaches 0, stop the interval
        if (--timer < 0) {
            clearInterval(interval);
            timerElement.textContent = "Time's up!";
            // Optionally, automatically submit the quiz when time is up
            submitQuiz();
        }
    }, 1000);
}

// Function to submit the quiz when time is up
function submitQuiz() {
    alert("Time's up! Submitting your quiz...");
    // Submit the form or perform any other action
    document.getElementById('save-all-answers').click(); // Trigger the submit button
}
</script>

<script>
// Add an event listener to the logout button
document.getElementById("logout-button").addEventListener("click", function() {
    // Redirect to a PHP script that destroys the session
    window.location.href = "logout.php";
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let overlay = document.getElementById("overlay");
    let cancelButton = document.getElementById("cancel-btn");

    if (cancelButton) {
        cancelButton.addEventListener("click", function () {
            overlay.style.display = "none"; // Hide overlay
        });
    }
});
</script>
<!-- Move script to the bottom for better performance -->
<script src="js/loader.js"></script>
</body>
</html>
