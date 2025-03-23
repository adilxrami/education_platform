<?php
// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$dbname = "university_exam";
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Create connection
$connection = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Start session
    session_start();

    // Get form data
    $user_type = $_POST['login_user_type'];
    $username = $_POST['university_number'] ?? $_POST['email'];
    $password = $_POST['password'];

    // Query based on user type
    if ($user_type === 'student') {
        $query = "SELECT * FROM students WHERE university_number = ?";
    } elseif ($user_type === 'professor') {
        $query = "SELECT * FROM professors WHERE email = ?";
    } elseif ($user_type === 'admin') {
        $query = "SELECT * FROM admins WHERE email = ?";
    } else {
        die("Invalid user type.");
    }

    // Prepare the statement
    $stmt = $connection->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user record
        $row = $result->fetch_assoc();

        // Check password
        $stored_password = $row['student_password'] ?? $row['passsword'] ?? $row['password'];
        if (password_verify($password, $stored_password)) {
            // Store session variables
            $_SESSION['user_type'] = $user_type;
            $_SESSION['university_number'] = $row['university_number'] ?? null;
            $_SESSION['email'] = $row['email'] ?? null;

            // Redirect based on user type
            if ($user_type === 'student') {
                header("Location: exam_dashboard.php");
            } elseif ($user_type === 'professor') {
                header("Location: professor_dashboard.php");
            } elseif ($user_type === 'admin') {
                header("Location: dashboard2.php");
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }

    // Close connection
    $stmt->close();
    $connection->close();
}
?>
