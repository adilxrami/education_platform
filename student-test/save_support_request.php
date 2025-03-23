<?php
$host = 'localhost'; // Database host
$dbname = 'university_exam'; // Database name (corrected typo)
$username = 'root'; // Database username
$password = ''; // Database password

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create table if not exists
    $table_creation_sql = "
    CREATE TABLE IF NOT EXISTS support_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        issue TEXT NOT NULL,
        submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );";

    $pdo->exec($table_creation_sql);

    // Retrieve form data and sanitize
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['name'], $_POST['email'], $_POST['issue'])) {
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $issue = htmlspecialchars($_POST['issue']);

            // Validate form data
            if (!empty($name) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($issue)) {
                // Insert data into the database
                $stmt = $pdo->prepare("INSERT INTO support_requests (name, email, issue) VALUES (:name, :email, :issue)");
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':issue', $issue);
                $stmt->execute();

                echo 'Support request submitted successfully!';
            } else {
                echo 'Invalid input. Please ensure all fields are filled correctly, and the email is valid.';
            }
        } else {
            echo 'Required fields are missing. Please ensure that all fields are filled.';
        }
    }
} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
}
?>
