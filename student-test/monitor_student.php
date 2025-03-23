<?php
// Start session for security
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
    die("<p class='error'>Connection failed: " . $connection->connect_error . "</p>");
}

// Check if student_number is sent via POST
$student_number = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_number'])) {
    $student_number = $connection->real_escape_string($_POST['student_number']);
} else {
    die("<p class='error'>Invalid access. Please select a student from the dashboard.</p>");
}

// Secure Query to Get Unique Student Answers (No Duplicates)
$query_answers = $connection->prepare("
    SELECT DISTINCT sa.university_number, sa.exam_id, sa.question_id, sa.answer AS student_answer
    FROM student_answers sa
    WHERE sa.university_number = ?
    ORDER BY sa.exam_id, sa.question_id
");
$query_answers->bind_param("s", $student_number);
$query_answers->execute();
$result_answers = $query_answers->get_result();

// Secure Query to Get Correct Answers
$query_correct_answers = $connection->prepare("
    SELECT eq.exam_id, eq.question_id, eq.correct_answer
    FROM exam_questions eq
    ORDER BY eq.exam_id, eq.question_id
");
$query_correct_answers->execute();
$result_correct_answers = $query_correct_answers->get_result();

// Secure Query to Get Correct Answers Summary
$query_correct_summary = $connection->prepare("
    SELECT sa.university_number, sa.exam_id, 
           COUNT(DISTINCT sa.question_id) AS correct_answers,
           (SELECT COUNT(*) FROM exam_questions eq WHERE eq.exam_id = sa.exam_id) AS total_questions
    FROM student_answers sa
    JOIN exam_questions eq 
        ON sa.question_id = eq.question_id 
        AND sa.exam_id = eq.exam_id
    WHERE sa.university_number = ?
    AND LOWER(TRIM(sa.answer)) = LOWER(TRIM(eq.correct_answer))
    GROUP BY sa.university_number, sa.exam_id
    ORDER BY sa.exam_id, correct_answers DESC
");
$query_correct_summary->bind_param("s", $student_number);
$query_correct_summary->execute();
$result_correct_summary = $query_correct_summary->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Student</title>
    <link rel="stylesheet" href="css/monitor_student.css">
</head>
<body>

<div class="container">
    <h1>Exam Results for Student: <?= htmlspecialchars($student_number) ?></h1>

    <!-- Student Answers Table (Now Without Duplicates) -->
    <h2>Student Answers</h2>
    <?php if ($result_answers->num_rows > 0) : ?>
        <table>
            <tr>
                <th>Exam ID</th>
                <th>Question ID</th>
                <th>Student Answer</th>
            </tr>
            <?php while ($row = $result_answers->fetch_assoc()) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['exam_id']) ?></td>
                    <td><?= htmlspecialchars($row['question_id']) ?></td>
                    <td><?= htmlspecialchars($row['student_answer']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else : ?>
        <p>No student answers found.</p>
    <?php endif; ?>

    <!-- Correct Answers Table -->
    <h2>Correct Answers</h2>
    <?php if ($result_correct_answers->num_rows > 0) : ?>
        <table>
            <tr>
                <th>Exam ID</th>
                <th>Question ID</th>
                <th>Correct Answer</th>
            </tr>
            <?php while ($row = $result_correct_answers->fetch_assoc()) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['exam_id']) ?></td>
                    <td><?= htmlspecialchars($row['question_id']) ?></td>
                    <td><?= htmlspecialchars($row['correct_answer']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else : ?>
        <p>No correct answers found.</p>
    <?php endif; ?>

    <!-- Correct Answers Summary Table -->
    <h2>Correct Answers Summary</h2>
    <?php if ($result_correct_summary->num_rows > 0) : ?>
        <table>
            <tr>
                <th>Exam ID</th>
                <th>Correct Answers</th>
                <th>Total Questions</th>
                <th>Success Percentage</th>
            </tr>
            <?php while ($row = $result_correct_summary->fetch_assoc()) : 
                $success_percentage = ($row['total_questions'] > 0) ? 
                    round(($row['correct_answers'] / $row['total_questions']) * 100, 2) : 0;
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['exam_id']) ?></td>
                    <td><?= $row['correct_answers'] ?></td>
                    <td><?= $row['total_questions'] ?></td>
                    <td><?= $success_percentage ?>%</td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else : ?>
        <p>No results found.</p>
    <?php endif; ?>

</div>

<script src="js/monitor_student.js"></script>
</body>
</html>

<?php
// Close prepared statements and connection
$query_answers->close();
$query_correct_answers->close();
$query_correct_summary->close();
$connection->close();
?>
