<?php
session_start();

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

// Ensure that the university number is passed correctly through the URL or session
$university_number = isset($_GET['university_number']) 
    ? htmlspecialchars($_GET['university_number']) 
    : (isset($_SESSION['university_number']) ? $_SESSION['university_number'] : 'Unknown Number');
$_SESSION['university_number'] = $university_number; // Store in session for future reference

if ($university_number === 'Unknown Number') {
    echo "University number is missing from the URL.";
    exit;
}

// Fetch student name
$query = "SELECT student_name FROM students WHERE university_number = ?";
$stmt = $connection->prepare($query);

// Check if the query was prepared correctly
if (!$stmt) {
    die("Query preparation failed: " . $connection->error);
}

$stmt->bind_param("s", $university_number);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();

// Handle incoming POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['exam_id'], $_POST['exam_title'], $_POST['university_number'], $_POST['exam_duration'])) {
        // Assign global variables
        $exam_id = htmlspecialchars($_POST['exam_id']);
        $exam_title = htmlspecialchars($_POST['exam_title']);
        $university_number = htmlspecialchars($_POST['university_number']);
        $exam_duration = intval($_POST['exam_duration']);

        // Store exam title in session for future reference
        $_SESSION['exam_title'] = $exam_title;

    } elseif (isset($_POST['answer'])) {
        $user_answer = $_POST['answer'];
        $_SESSION['answers'][$_SESSION['current_question']] = $user_answer; // Save the answer for the current question
    } elseif (isset($_POST['logout'])) {
        // Handle logout: Clear session and redirect
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 3600, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        session_destroy();
        header("Location: index.php");
        exit();
    }
  // Check if the exam duration is set in the POST data
if (isset($_POST['exam_duration'])) {
    // Store the exam duration in the session
    $_SESSION['exam_duration'] = intval($_POST['exam_duration']);
    $_SESSION['start_time'] = time(); // Record the start time
}

// Calculate the remaining time
if (isset($_SESSION['start_time']) && isset($_SESSION['exam_duration'])) {
    $elapsedTime = time() - $_SESSION['start_time'];
    $remainingTime = $_SESSION['exam_duration'] * 60 - $elapsedTime; // Convert to seconds
} else {
    $remainingTime = 0; // No timer set
}

}

// Ensure $exam_title has a value before using it
$exam_title = isset($_POST['exam_title']) ? htmlspecialchars($_POST['exam_title']) : 
(isset($_SESSION['exam_title']) ? $_SESSION['exam_title'] : 'Default Exam Title');

// Get total questions count
$total_questions_query = "SELECT COUNT(*) AS total FROM exam_questions";
$total_questions_result = $connection->query($total_questions_query);
$total_questions = $total_questions_result->fetch_assoc()['total'];

// Fetch the current question
$current_question = isset($_GET['question']) ? intval($_GET['question']) : 1;

// Fetch question details
$question_query = "SELECT * FROM exam_questions LIMIT 1 OFFSET ?";
$question_stmt = $connection->prepare($question_query);

if (!$question_stmt) {
    die("Failed to prepare question query: " . $connection->error);
}

$offset = $current_question - 1;
$question_stmt->bind_param("i", $offset);
$question_stmt->execute();
$question_result = $question_stmt->get_result();

if ($question_result->num_rows > 0) {
    $question = $question_result->fetch_assoc();
} else {
    die("No question found.");
}

// Fetch options for multiple-choice questions
$options = [];
if ($question['question_type'] === 'multiple-choice') {
    $options_query = "SELECT option_key, option_value FROM question_options WHERE question_id = ?";
    $options_stmt = $connection->prepare($options_query);

    if (!$options_stmt) {
        die("Failed to prepare options query: " . $connection->error);
    }

    $options_stmt->bind_param("i", $question['question_id']);
    $options_stmt->execute();
    $options_result = $options_stmt->get_result();

    while ($option = $options_result->fetch_assoc()) {
        $options[$option['option_key']] = $option['option_value'];
    }
}


// Get the saved answer for the current question
$saved_answer = isset($_SESSION['answers'][$current_question]) ? $_SESSION['answers'][$current_question] : null;

// Close the database connection
$connection->close();
?>
