<?php
// Database connection details
$host = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "university_exam"; // Replace with your database name

// Establish DB connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve all students
$sql = "SELECT university_number, student_name, student_phone, student_email, student_department, student_gender FROM students";
$result = $conn->query($sql);

// Check if there are students
$student_count = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $student_count++;
    }
} else {
    echo "No students found.";
}

// Query to count exams
$count_query = "SELECT COUNT(*) AS exam_count FROM student_exam";
$result = $conn->query($count_query);

if ($result) {
    $row = $result->fetch_assoc();
    $exam_count = $row['exam_count'];
} else {
    echo "Error: " . $conn->error;
}

// Query to count admins
$admin_query = "SELECT COUNT(*) AS admin_count FROM admins";
$admin_result = $conn->query($admin_query);

if ($admin_result) {
    $admin_row = $admin_result->fetch_assoc();
    $admin_count = $admin_row['admin_count'];
} else {
    echo "Error: " . $conn->error;
}
// Query to count professors
$professor_query = "SELECT COUNT(*) AS professor_count FROM professors";
$professor_result = $conn->query($professor_query);

if ($professor_result) {
    $professor_row = $professor_result->fetch_assoc();
    $professor_count = $professor_row['professor_count'];
} else {
    echo "Error: " . $conn->error;
}

// Close the connection
$conn->close();
?>
