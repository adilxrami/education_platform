<?php
// Start the session
session_start();

// Database connection details
$host = "localhost"; // Change if your database server is not localhost
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "university_exam"; // Replace with your database name

// Initialize connection as null to avoid undefined variable issues
$connection = null;

// Create connection
$connection = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the student number and department from the POST request
    $student_number = $_POST['student_number'];
    $department = $_POST['department'];

    // Validate inputs to prevent SQL injection
    $student_number = $connection->real_escape_string($student_number);
    $department = $connection->real_escape_string($department);

    // Delete the record from the grades table
    $deleteQuery = "DELETE FROM grades 
                    WHERE university_number = '$student_number' 
                    AND department = '$department'";

    if ($connection->query($deleteQuery) === TRUE) {
        echo "Record deleted successfully.";
    } else {
        echo "Error deleting record: " . $connection->error;
    }

    // Optionally, delete related records from other tables if needed
    // Example: DELETE FROM students WHERE university_number = '$student_number'

    // Redirect back to the main page after deletion
    header("Location: index.php");
    exit();
}

// Close the database connection
$connection->close();
?>