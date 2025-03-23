
<?php

// Database connection details
$host = "localhost"; // Change if your database server is not localhost
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "university_exam"; // Replace with your database name

// Establish DB connection
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database if it doesn't exist
$create_db_sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($create_db_sql) === TRUE) {
    // Select the database
    $conn->select_db($dbname);
} else {
    die("Error creating database: " . $conn->error);
}

// SQL to create tables if not exist
$table_creation_sql = "
CREATE TABLE IF NOT EXISTS students (
    university_number VARCHAR(13) NOT NULL PRIMARY KEY,
    student_name VARCHAR(255),
    student_phone VARCHAR(20),
    student_email VARCHAR(255),
    student_password VARCHAR(255),
    student_department VARCHAR(255),
    student_gender ENUM('Male', 'Female')
);

CREATE TABLE IF NOT EXISTS student_exam (
    exam_id INT AUTO_INCREMENT PRIMARY KEY,
    exam_title VARCHAR(255) NOT NULL,
    exam_duration INT NOT NULL,
    department VARCHAR(255) NOT NULL,
    exam_tips VARCHAR(1000)
);

CREATE TABLE IF NOT EXISTS student_exam_selection (
    selection_id INT AUTO_INCREMENT PRIMARY KEY,
    university_number CHAR(13),
    exam_id INT,
    selected_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (university_number) REFERENCES students(university_number),
    FOREIGN KEY (exam_id) REFERENCES student_exam(exam_id)
);

CREATE TABLE IF NOT EXISTS exam_questions (
    question_id INT AUTO_INCREMENT PRIMARY KEY,
    exam_id INT NOT NULL,
    question_text TEXT NOT NULL,
    question_type VARCHAR(255),
    correct_answer TEXT NOT NULL,
    FOREIGN KEY (exam_id) REFERENCES student_exam(exam_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS question_options (
    option_id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    option_key VARCHAR(10) NOT NULL,
    option_value VARCHAR(255) NOT NULL,
    FOREIGN KEY (question_id) REFERENCES exam_questions(question_id)
);

CREATE TABLE IF NOT EXISTS grades (
    grade_id INT AUTO_INCREMENT PRIMARY KEY,
    university_number VARCHAR(13) NOT NULL,
    department VARCHAR(255) NOT NULL,
    student_subject VARCHAR(255) NOT NULL,
    student_grade VARCHAR(255),
    total_subject_mark INT NOT NULL,
    subject_date DATE NOT NULL,
    FOREIGN KEY (university_number) REFERENCES students(university_number)
);

CREATE TABLE IF NOT EXISTS professors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    phone VARCHAR(20),
    email VARCHAR(255),
    password VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    phone VARCHAR(20),
    email VARCHAR(255),
    password VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS student_answers (
    answer_id INT AUTO_INCREMENT PRIMARY KEY,
    university_number CHAR(13) NOT NULL,
    exam_id INT NOT NULL,
    question_id INT NOT NULL,
    answer VARCHAR(255) NOT NULL,
    FOREIGN KEY (university_number) REFERENCES students(university_number),
    FOREIGN KEY (exam_id) REFERENCES student_exam(exam_id),
    FOREIGN KEY (question_id) REFERENCES exam_questions(question_id)
);
";

// Execute table creation queries
if ($conn->multi_query($table_creation_sql)) {
    // Process results from multiple queries
    while ($conn->next_result()) {
        // You can add error handling or logging here if needed
    }

} else {
    echo "Error creating tables: " . $conn->error . "<br>";
}

// Handle form submission first
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['signup-user-type'] ?? '';
    
    if (empty($role)) {
        die(json_encode(["status" => "error", "message" => "Role is not provided."]));
    }

    $validRoles = ['student', 'professor', 'admin'];

    if (!in_array($role, $validRoles)) {
        die(json_encode(["status" => "error", "message" => "Invalid role provided."]));
    }

    // Establish DB connection
    $conn = new mysqli($host, $username, $password);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create the database if it doesn't exist
    $create_db_sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($create_db_sql) === TRUE) {
        // Select the database
        $conn->select_db($dbname);
// Create connection
$connection = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}




        // Execute table creation query
        if ($conn->multi_query($table_creation_sql)) {
            while ($conn->next_result()) {
                // Process results from multiple queries
            }
            echo "Tables created successfully.<br>";
        } else {
            echo "Error creating tables: " . $conn->error . "<br>";
        }
    } else {
        die("Error creating database: " . $conn->error);
    }

    // Gather form data
    $name = $conn->real_escape_string($_POST['name'] ?? '');
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $password = password_hash($_POST['password'] ?? '', PASSWORD_BCRYPT); // Hash the password
    $university_number = $conn->real_escape_string($_POST['university_number'] ?? '');

    $department = $conn->real_escape_string($_POST['department'] ?? '');
    $gender = $conn->real_escape_string($_POST['gender'] ?? '');
if (isset($_POST['university_number']) && !empty($_POST['university_number'])) {
    $university_number = $conn->real_escape_string($_POST['university_number']);
} else {
    // Handle the case where the university number is not provided
    echo "University number is required.";
}

    // Insert data based on role
    $insert_sql = null;
    echo "Role: " . $role;
    switch ($role) {
        case "student":
            $insert_sql = "INSERT INTO students (university_number, student_name, student_phone, student_email, student_password, student_department, student_gender)
            VALUES ('$university_number', '$name', '$phone', '$email', '$password', '$department', '$gender')";
            break;
    
        case "professor":
            $insert_sql = "INSERT INTO professors (name, phone, email, password)
            VALUES ('$name', '$phone', '$email', '$password')";
            break;
    
        case "admin":
            $insert_sql = "INSERT INTO admins (name, phone, email, password)
            VALUES ('$name', '$phone', '$email', '$password')";
            break;
    
        default:
            // Handle unexpected roles if necessary
            break;
    }
    
    if (!isset($insert_sql)) {
        die("Error: SQL query is not defined.");
    }
    
    if ($insert_sql) {
        if ($conn->query($insert_sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => ucfirst($role) . " added successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database Error: " . $conn->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "SQL query was not defined."]);
    }

    // Close the connection
    $conn->close();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="css/signup.css">
    <link rel="stylesheet" href="css/loader.css">
</head>
<style>
    a{
        text-decoration:none
    }
</style>
<body >

<div class="loader">
  <div class="load-inner load-one"></div>
  <div class="load-inner load-two"></div>
  <div class="load-inner load-three"></div>
  <span class="loader-text">Loading</span>
</div>
<div class="container">
    <div class="box">      
        <div class="text-center"><img src="picture/university.png" class=""></div>   
        <h3 class="text-center">welcome to sign up </h3>
        <form id="signup-form" method="post">  
            <label for="signup-user-type">User Type:</label>
            <select id="signup-user-type" name="signup-user-type" required>
                <option value="" disabled selected>--Select User Type--</option>
                <option value="student">Student</option>
                <option value="professor">Professor</option>
                <option value="admin">University Administration</option>
            </select>
    
            <div id="dynamic-fields"></div>
            <a href="index.php" target="_self">Sign in Here</a>
            <button id="signupButton" type="submit">Sign Up</button>
        </form>
    </div>
</div>

<footer class="footer">
    <div class="footer-links">
        <a href="#">Home</a>
        <a href="#">About Us</a>
        <a href="#">Contact</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
    </div>
    <p>&copy; 2025 Exam Website. All Rights Reserved.</p>
</footer>

<script src="js/jquery-3.7.1.min.js"></script>
<script src="js/loader.js"></script>
<script>
$(document).ready(function () {
    const fieldSets = {
        student: `
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <span class="error" id="name-error"></span>

            <label for="university-number">University Number:</label>
            <input type="text" id="university-number" name="university_number" required>
            <span class="error" id="university-number-error"></span>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required>
            <span class="error" id="phone-error"></span>

            <label for="department">Department:</label>
            <select id="department" name="department" required>
                <option value="">--Select department--</option>
                <option value="IS">Information System</option>
                <option value="IT">Information Technology</option>
                <option value="CS">Computer Science</option>
            </select>
            <span class="error" id="department-error"></span>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <span class="error" id="email-error"></span>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="">--Select Gender--</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
            <span class="error" id="gender-error"></span>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <span class="error" id="password-error"></span>

            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Retype your password" required>
            <span class="error" id="confirm-password-error"></span>
        `,
        professor: `
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <span class="error" id="name-error"></span>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required>
            <span class="error" id="phone-error"></span>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <span class="error" id="email-error"></span>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <span class="error" id="password-error"></span>

            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Retype your password" required>
            <span class="error" id="confirm-password-error"></span>
        `,
        admin: `
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <span class="error" id="name-error"></span>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required>
            <span class="error" id="phone-error"></span>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <span class="error" id="email-error"></span>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <span class="error" id="password-error"></span>

            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Retype your password" required>
            <span class="error" id="confirm-password-error"></span>
        `
    };

    $('#signup-user-type').on('change', function () {
        const userType = $(this).val();
        $('#dynamic-fields').empty();
        if (fieldSets[userType]) {
            $('#dynamic-fields').html(fieldSets[userType]);
        }
    });

    $('#signup-form').on('submit', function (e) {
        e.preventDefault();

        let isValid = true;

        // Validate fields
        $('#signup-form [required]').each(function () {
            const value = $(this).val().trim();
            const id = $(this).attr('id');
            const errorField = $(`#${id}-error`);

            if (!value) {
                isValid = false;
                errorField.text('This field is required.');
            } else {
                errorField.text('');
            }
        });

        // Validate passwords match
        const password = $('#password').val().trim();
        const confirmPassword = $('#confirm-password').val().trim();

        if (password && confirmPassword && password !== confirmPassword) {
            isValid = false;
            $('#confirm-password-error').text('Passwords do not match.');
        }

        if (isValid) {
            $.ajax({
                url: 'signup.php', // Replace with your endpoint
                type: 'POST',
                data: $('#signup-form').serialize(),
                success: function (response) {
                    alert('Form submitted successfully!');
                },
                error: function (error) {
                    alert('Failed to submit the form.');
                }
            });
        }
    });
});
</script>
<script>
    document.getElementById("signupButton").addEventListener("click", function(event) {window.location.href = "index.php";});
</script>

</body>
</html>


