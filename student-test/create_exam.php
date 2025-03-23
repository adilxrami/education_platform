<?php
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize exam data
    if (isset($_POST['exam_title'], $_POST['exam_duration'], $_POST['department'], $_POST['exam_tips'])) {
        $exam_title = $connection->real_escape_string($_POST['exam_title']);
        $exam_duration = intval($_POST['exam_duration']);
        $department = $connection->real_escape_string($_POST['department']);
        $exam_tips = $connection->real_escape_string($_POST['exam_tips']);

        // Insert the exam into the database
        $query = "INSERT INTO student_exam (exam_title, exam_duration, department, exam_tips) VALUES (?, ?, ?, ?)";
        $stmt = $connection->prepare($query);

        if (!$stmt) {
            die("Failed to prepare exam insert statement: " . $connection->error);
        }

        $stmt->bind_param("siss", $exam_title, $exam_duration, $department, $exam_tips);

        if ($stmt->execute()) {
            // Get the last inserted exam_id
            $exam_id = $connection->insert_id;

            // Check if questions are provided
            if (isset($_POST['questions']) && is_array($_POST['questions'])) {
                foreach ($_POST['questions'] as $question) {
                    if (!empty($question['text']) && !empty($question['type']) && isset($question['correct_answer'])) {
                        $question_text = $connection->real_escape_string($question['text']);
                        $question_type = $connection->real_escape_string($question['type']);
                        $correct_answer = $connection->real_escape_string($question['correct_answer']); // Get the correct answer

                        // Prepare query for inserting questions
                        $question_query = "INSERT INTO exam_questions (exam_id, question_text, question_type, correct_answer) VALUES (?, ?, ?, ?)";
                        $question_stmt = $connection->prepare($question_query);
                        $question_stmt->bind_param("isss", $exam_id, $question_text, $question_type, $correct_answer); // Bind correct_answer

                        if ($question_stmt->execute()) {
                            $question_id = $connection->insert_id; // Get last inserted question ID

                            // If multiple-choice question, insert options
                            if ($question_type === 'multiple-choice' && isset($question['options'])) {
                                foreach ($question['options'] as $option_key => $option_value) {
                                    if (!empty($option_value)) {
                                        $option_query = "INSERT INTO question_options (question_id, option_key, option_value) VALUES (?, ?, ?)";
                                        $option_stmt = $connection->prepare($option_query);
                                        $option_stmt->bind_param("iss", $question_id, $option_key, $option_value);
                                        $option_stmt->execute();
                                    }
                                }
                            }
                        } else {
                            echo "Failed to add question: " . $question_stmt->error . "<br>";
                        }
                    }
                }
            }
            echo "Exam created successfully with questions!<br>";
        } else {
            echo "Failed to create exam: " . $stmt->error . "<br>";
        }

        $stmt->close();
    } else {
        echo "Required fields are missing: 'exam_title', 'exam_duration', 'department', 'exam_tips'.<br>";
    }
}

// Close database connection
$connection->close();
?>
