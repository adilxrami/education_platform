<?php require_once('header.php')
?>
<?php

// Initialize $remainingTime with a default value
$remainingTime = 0;

// Calculate the remaining time if session variables are set
if (isset($_SESSION['start_time']) && isset($_SESSION['exam_duration'])) {
    $elapsedTime = time() - $_SESSION['start_time'];
    $remainingTime = $_SESSION['exam_duration'] * 60 - $elapsedTime; // Convert to seconds

    // Ensure remaining time is not negative
    if ($remainingTime < 0) {
        $remainingTime = 0;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Dashboard</title>
    <link rel="stylesheet" href="css/exam_entry.css">
    <link rel="stylesheet" href="css/quiz_1.css">
    <style>
        /*timer part*/
@import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap');

.timer {
    font-family: 'Orbitron', sans-serif; /* Digital clock style font */
    font-size: 2.5rem; /* Large text */
    font-weight: bold;
    color:rgb(252, 100, 100); /* Bright red color */
    text-align: center;
    background:rgb(44, 181, 190); /* Dark background for contrast */
    padding: 15px 30px;
    border-radius: 10px;
    display: inline-block;
    box-shadow: 0px 4px 10px rgba(255, 68, 68, 0.5); /* Red glow effect */
    transition: transform 0.2s ease-in-out;
    float:right;
}
.question h4 {
    font-family: 'Nunito', sans-serif; /* Same font as timer */
    font-size: 1rem; /* Ensure consistency */
    font-weight: bold;
    color: #222; /* Dark color for readability */
    margin-bottom: 10px;
}

.question p {
    font-family: 'Nunito', sans-serif; /* Same font */
    font-size: 1.6rem; /* Adjust for readability */
    color: #444;
    line-height: 1.5;
}


/* Smooth scale effect when updating */
.timer.update {
    transform: scale(1.1);
}

/* Add flashing effect when time is below 30 seconds */
@keyframes flash {
    0% { color: #ff4444; background: black; }
    50% { color: white; background: red; }
    100% { color: #ff4444; background: black; }
}

.timer.low-time {
    animation: flash 1s infinite;
}


    </style>
</head>
<script>document.addEventListener("DOMContentLoaded", function () {
    startTimer();
});

function startTimer() {
    const timerElement = document.getElementById('timer');
    const submitButton = document.getElementById('save-all-answers');
    const totalTime = parseInt(timerElement.getAttribute("data-total-time"), 10); // Get total exam time from the attribute
    const halfTime = totalTime / 2; // Calculate half of the total time

    // Hide submit button initially
    submitButton.style.display = "none";

    async function updateTimer() {
        try {
            const response = await fetch('get_remaining_time.php?nocache=' + new Date().getTime(), {
                method: 'GET',
                headers: { 'Cache-Control': 'no-cache, no-store, must-revalidate' }
            });
            const data = await response.json();

            if (data.remainingTime >= 0) {
                const minutes = Math.floor(data.remainingTime / 60);
                const seconds = data.remainingTime % 60;
                timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

                // Show submit button when half time has passed
                if (data.remainingTime <= halfTime) {
                    submitButton.style.display = "inline-block"; // Show the button
                }
            }
        } catch (error) {
            console.error("Timer error:", error);
        }
    }

    // Call updateTimer every second
    setInterval(updateTimer, 1000);
    updateTimer(); // Run once immediately
}

// Function to submit the quiz when time is up
function submitQuiz() {
    alert("Time's up! Submitting your quiz...");
    document.getElementById('save-all-answers').click(); // Trigger the submit button
}

// Start the timer when the page loads
startTimer();

    </script>

<body>
    <div class="quiz-dashboard">
        <aside class="sidebar">
            <div class="header">Quiz Time</div>
            <ul>
                <li class="active"><span>Dashboard</span></li>
            </ul>
            <div class="logout"  id="logout-button">
                <div>
                    <img class="icon logout1" src="picture/logout.png" width="20px" height="20px">
                </div>Log Out
            </div>
        </aside>
        <main>
            <div class="top-bar">
                <input id="search-input" type="text" placeholder="Enter question number...">
                <button id="search-btn" class="search-btn">Go</button>
            
                <div class="user-profile">
                    <span class="profile-icon">M</span>
                    <span class="username"><?php echo htmlspecialchars($student_name); ?></span>
                </div>
            </div>
            <div class="quiz-section">
    <h2>Quiz: <?php echo htmlspecialchars($exam_title); ?> </h2>
  <!-- Timer Display -->
  <div class="timer" id="timer" data-total-time="<?php echo $_SESSION['exam_duration'] * 60; ?>">
        <?php
        // Display the initial timer value
        if ($remainingTime > 0) {
            $minutes = floor($remainingTime / 60);
            $seconds = $remainingTime % 60;
            echo sprintf("%02d:%02d", $minutes, $seconds);
        } else {
            echo "00:00";
        }
        ?>
    </div>
    <p>Answer the question below</p>
    <div class="quiz-content">
    <div class="question">
        <h4>Question <?php echo $current_question; ?>/<?php echo $total_questions; ?></h4>
        <p><?php echo $question['question_text']; ?></p>
    </div>

</div>
<div class="options">
        <?php
        switch ($question['question_type']) {
            case 'true-false':
                echo '<label><input type="radio" name="answer[' . $current_question . ']" value="true"> True</label>';
                echo '<label><input type="radio" name="answer[' . $current_question . ']" value="false"> False</label>';
                break;

            case 'multiple-choice':
                if (!empty($options)) {
                    foreach ($options as $key => $value) {
                        echo "<label>
                            <input type='radio' name='answer[" . $question['question_id'] . "]' value='" . htmlspecialchars($key) . "'> 
                            " . htmlspecialchars($value) . "
                        </label><br>";
                    }
                } else {
                    echo '<p>No options available for this question.</p>';
                }
                break;

            default:
                echo '<p>Unsupported question type.</p>';
        }
        ?>
    </div>
</div>





<div class="navigation-buttons">
<form id="save-answers-form" action="quiz_finish.php" method="POST">
    <input type="hidden" name="university_number" value="<?php echo $university_number; ?>">
    <input type="hidden" name="exam_id" value="<?php echo htmlspecialchars($exam_id); ?>">
    <input type="hidden" name="answers" id="answers">
    <button type="button" id="save-all-answers">Submit All Answers</button>
</form>

    <!-- Next Question Navigation -->
    <form method="GET" action="" style="display: inline;">
        <input type="hidden" name="question" value="<?php echo min($total_questions, $current_question + 1); ?>">
        <button type="submit" <?php echo $current_question >= $total_questions ? 'disabled' : ''; ?>>
            <?php echo $current_question < $total_questions ? 'Next' : 'Finish'; ?>
        </button>
    </form>

    <!-- Back Navigation -->
    <form method="GET" action="" style="display: inline;">
        <input type="hidden" name="question" value="<?php echo max(1, $current_question - 1); ?>">
        <button type="submit" <?php echo $current_question <= 1 ? 'disabled' : ''; ?>>Back</button>
    </form>
</div>

<div class="question-numbers">
    <?php for ($i = 1; $i <= $total_questions; $i++): ?>
        <a href="?question=<?php echo $i; ?>" class="<?php echo $i == $current_question ? 'active' : ''; ?>">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>
</div>


    </main>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    let saveButton = document.getElementById("save-all-answers");
    if (saveButton) {
        saveButton.addEventListener("click", function () {
            fetch("destroy_timer.php", { method: "POST" })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = "logout.php";
                        document.getElementById("save-answers-form").submit(); // Submit the form
                    }
                })
                .catch(error => console.error("Error:", error));
        });
    }
});
</script>

    <script>
// Add an event listener to the logout button
document.getElementById("logout-button").addEventListener("click", function() {
    // Redirect to a PHP script that destroys the session
    window.location.href = "logout.php";
});
</script>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/quiz.js"></script>
    
    <script>
// Object to store answers
let answers = {};

// Get total number of questions from PHP
const totalQuestions = <?php echo $total_questions; ?>;

function saveAnswer(event) {
    const questionNumber = <?php echo $current_question; ?>;
    const answerValue = event.target.value;
    
    // Debugging line to print question number and answer
    console.log("Saving answer for question:", questionNumber, "Answer:", answerValue);

    // Update the answers object
    answers[questionNumber] = answerValue;

    // Save to local storage
    localStorage.setItem("quiz_answers", JSON.stringify(answers));
}

// Restore the saved answer for the current question
function restoreAnswer() {
    // Retrieve saved answers from local storage
    const savedAnswers = JSON.parse(localStorage.getItem("quiz_answers")) || {};
    const questionNumber = <?php echo $current_question; ?>;

    // Check if there's a saved answer for the current question
    if (savedAnswers[questionNumber]) {
        const savedAnswer = savedAnswers[questionNumber];
        const radioButton = document.querySelector(`input[name="answer[${questionNumber}]"][value="${savedAnswer}"]`);
        if (radioButton) {
            radioButton.checked = true;
        }
    }

    // Populate the answers object with saved answers
    answers = savedAnswers;
    console.log(answers); // Add this line before saving the answers to see the data
}

// Check if all questions have been answered
function allAnswersCompleted() {
    // Compare number of answered questions with total number of questions
    for (let i = 1; i <= totalQuestions; i++) {
        if (!answers[i]) {
            return false;  // if any answer is missing, return false
        }
    }
    return true;
}

// Attach event listeners for saving answers
const radioButtons = document.querySelectorAll('input[type="radio"]');
radioButtons.forEach(radio => {
    radio.addEventListener('click', saveAnswer);
});

// Restore answers when the page loads
window.addEventListener('DOMContentLoaded', restoreAnswer);

// Handle Save All Answers button click
document.getElementById("save-all-answers").addEventListener("click", function () {
    // Ensure all questions have been answered
    if (allAnswersCompleted()) {
        // Save all answers to the hidden input
        document.getElementById("answers").value = JSON.stringify(answers);
        
        // Clear local storage
        localStorage.removeItem("quiz_answers");
        
        // Submit the form
        document.getElementById("save-answers-form").submit();
    } else {
        alert("Please answer all questions before submitting.");
    }
});
</script>
 
</body>
</html>
