<?php
// Database connection
// Database connection details
$host = "localhost"; // Change if your database server is not localhost
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "university_exam"; // Replace with your database name
$connection = new mysqli($host, $username, $password , $dbname );

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $student_id = $connection->real_escape_string($_POST['student-id']);
$check_query = "SELECT 1 FROM students WHERE university_number = ?";
$check_stmt = $connection->prepare($check_query);
$check_stmt->bind_param("s", $student_id);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows === 0) {
    die("Error: University number does not exist in the students table.");
}


    $department = $connection->real_escape_string($_POST['department']);
    $student_subject = $connection->real_escape_string($_POST['student-subject']);
    $grade  = isset($_POST['grade']) ? intval($_POST['grade']) : 0;
    $total_subject_mark = isset($_POST['total-subject-mark']) ? intval($_POST['total-subject-mark']) : 0;
    $subject_date = $connection->real_escape_string($_POST['subject-date']);

    // Insert the grade into the database
    $query = "
        INSERT INTO grades (university_number, department, student_subject, student_grade, total_subject_mark, subject_date)
        VALUES (?, ?, ?, ?, ?, ?)
    ";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ssssss", $student_id, $department, $student_subject, $grade, $total_subject_mark, $subject_date);

    if ($stmt->execute()) {
        echo "Grade inserted successfully!";
    } else {
        echo "Failed to insert grade: " . $stmt->error;
    }
    $stmt->close();
}

$connection->close();
?>
