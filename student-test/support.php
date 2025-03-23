<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/support.css"/>
    <style>
        .text-center {
            text-align: center;
        }
    </style>
    <title>Support Page</title>
    <script src="js/jquery-3.7.1.min.js"></script>
</head>
<body>
    <div class="text-center"><img src="picture/support.png" alt="Support"></div>
    <h1>Support</h1>
    <section class="support-section">
        <div class="support-item">
            <h3>Frequently Asked Questions (FAQs)</h3>
            <div class="faq-item">
                <button>How do I reset my password?</button>
                <div class="faq-answer">
                    <p>To reset your password, click the "Forgot Password" link on the login page, enter your email address, and follow the instructions sent to your email.</p>
                </div>
            </div>
            <div class="faq-item">
                <button>How do I contact technical support?</button>
                <div class="faq-answer">
                    <p>You can reach technical support by filling out the contact form below or emailing us directly at support@example.com.</p>
                </div>
            </div>
        </div>

        <div class="support-item">
            <h3>Contact Technical Support</h3>
            <form class="contact-form" id="support-form" method="post">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>

                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>

                <label for="issue">Describe Your Issue:</label>
                <textarea id="issue" name="issue" rows="5" placeholder="Describe your issue in detail" required></textarea>

                <button type="submit">Submit</button>
            </form>
        </div>
    </section>
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

    <script>
        $(document).ready(function () {
            $('#support-form').on('submit', function (e) {
                e.preventDefault(); // Prevent default form submission
                const formData = $(this).serialize();
                $.ajax({
                    url: 'support.php',
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        alert('Your request has been submitted successfully!');
                        $('#support-form')[0].reset(); // Reset the form
                    },
                    error: function () {
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
</body>
</html>
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
