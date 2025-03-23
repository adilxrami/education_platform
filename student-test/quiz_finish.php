<?php

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "university_exam";

$connection = new mysqli($host, $username, $password, $dbname);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the POST data
    $university_number = $_POST['university_number'] ?? null;
    $exam_id = isset($_POST['exam_id']) ?? null;
    $answers = json_decode($_POST['answers'], true); // Assuming JSON string input for answers

    // Validate input data
    if (!$university_number || !$exam_id || empty($answers)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    // Validate exam_id
    if (!isValidExamId($connection, $exam_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid exam ID.']);
        exit;
    }

    // Begin transaction
    $connection->begin_transaction();

    try {
        // Prepare the statement to insert or update answers
        $query = "
            INSERT INTO student_answers (university_number, exam_id, question_id, answer)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE answer = VALUES(answer)
        ";

        $stmt = $connection->prepare($query);

        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $connection->error);
        }

        foreach ($answers as $question_id => $answer) {
            // Validate question_id
            if (!isValidQuestionId($connection, $question_id, $exam_id)) {
                error_log("Invalid Question ID: $question_id for Exam ID: $exam_id");
                continue;
            }

            // Bind parameters and execute the statement
            $stmt->bind_param('siis', $university_number, $exam_id, $question_id, $answer);

            if (!$stmt->execute()) {
                throw new Exception("Error saving answer for Question ID $question_id: " . $stmt->error);
            }
        }

        // Commit the transaction
        $connection->commit();
        echo json_encode(['status' => 'success', 'message' => 'Answers saved successfully.']);
    } catch (Exception $e) {
        // Rollback on error
        $connection->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()]);
    } finally {
        $stmt->close();
        session_destroy(); // Destroy session after saving answers
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

// Function to validate exam_id
function isValidExamId($connection, $exam_id) {
    $query = "SELECT exam_id FROM student_exam WHERE exam_id = ?";
    $stmt = $connection->prepare($query);

    if (!$stmt) {
        error_log("Error preparing exam ID validation query: " . $connection->error);
        return false;
    }

    $stmt->bind_param('i', $exam_id);
    $stmt->execute();
    $stmt->store_result();

    $isValid = $stmt->num_rows > 0;
    $stmt->close();
    return $isValid;
}

// Function to validate question_id
function isValidQuestionId($connection, $question_id, $exam_id) {
    $query = "SELECT question_id FROM exam_questions WHERE question_id = ? AND exam_id = ?";
    $stmt = $connection->prepare($query);

    if (!$stmt) {
        error_log("Error preparing question ID validation query: " . $connection->error);
        return false;
    }

    $stmt->bind_param('ii', $question_id, $exam_id);
    $stmt->execute();
    $stmt->store_result();

    $isValid = $stmt->num_rows > 0;
    $stmt->close();
    return $isValid;
}

// Destroy session after processing
session_destroy();

// Redirect the user to the login page or home page
header("Location: successful.php"); // Replace "successful" with your desired destination
exit();
$connection->close();

?>
