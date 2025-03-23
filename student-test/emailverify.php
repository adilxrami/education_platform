<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password & Verify Email</title>

  <link rel="stylesheet" href="css/email.css">
</head>
<body>
  <div class="container">
    <h1>Forgot Password</h1>
    <form id="forgotPasswordForm">
      <label for="email">Enter your email:</label>
      <input type="email" id="email" placeholder="example@example.com" required>
      <button type="submit">Submit</button>
    </form>

    <div id="verificationSection" style="display: none;">
      <h1>Verify Email</h1>
      <form id="verifyEmailForm">
        <label for="code">Enter verification code:</label>
        <input type="text" id="code" placeholder="Verification Code" required>
        <button type="submit">Verify</button>
      </form>
    </div>
  </div>
  <div id="newPasswordSection" style="display: none;">
  <h1>Create New Password</h1>
  <form id="newPasswordForm">
    <label for="newPassword">Enter new password:</label>
    <input type="password" id="newPassword" placeholder="New Password" required>
    <button type="submit">Update Password</button>
  </form>
</div>

  <script src="js/email.js"></script>
  <script>
    // Handle email submission
document.getElementById('forgotPasswordForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const email = document.getElementById('email').value;

    fetch('email_verification.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert(data.message);
            document.getElementById('verificationSection').style.display = 'block';
        } else if (data.error) {
            alert(data.error);
        }
    })
    .catch(error => console.error('Error:', error));
});

// Handle email verification
document.getElementById('verifyEmailForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const code = document.getElementById('code').value;

    fetch('email_verification.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, code })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert(data.message);
            document.getElementById('newPasswordSection').style.display = 'block'; // Show New Password Section
            document.getElementById('verificationSection').style.display = 'none'; // Hide Verification Section
        } else if (data.error) {
            alert(data.error);
        }
    })
    .catch(error => console.error('Error:', error));
});

// Handle password update
document.getElementById('newPasswordForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const newPassword = document.getElementById('newPassword').value;

    fetch('email_verification.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, newPassword })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert(data.message);
            window.location.reload(); // Reload the page or redirect to login
        } else if (data.error) {
            alert(data.error);
        }
    })
    .catch(error => console.error('Error:', error));
});

</script>
</body>
</html>

<?php
<?php
// Database connection details
$host = "localhost"; // Database server hostname
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "university_exam"; // Replace with your database name

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If needed, set the charset (optional, but recommended for multi-language support)
$conn->set_charset("utf8");

// Return or use $conn in your scripts
?>


function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$request = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($request['email']) && !isset($request['code']) && !isset($request['newPassword'])) {
        // Forgot password logic
        $email = sanitizeInput($request['email']);
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $verificationCode = rand(100000, 999999);
            $stmt = $conn->prepare("UPDATE users SET verification_code = ? WHERE email = ?");
            $stmt->bind_param("is", $verificationCode, $email);
            $stmt->execute();
            mail($email, "Your Verification Code", "Your code is: $verificationCode");
            echo json_encode(["message" => "Verification code sent to your email."]);
        } else {
            echo json_encode(["error" => "Email not found."]);
        }
    } elseif (isset($request['email']) && isset($request['code'])) {
        // Verify email logic
        $email = sanitizeInput($request['email']);
        $code = sanitizeInput($request['code']);
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND verification_code = ?");
        $stmt->bind_param("si", $email, $code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE users SET is_verified = 1, verification_code = NULL WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            echo json_encode(["message" => "Email verified successfully."]);
        } else {
            echo json_encode(["error" => "Invalid verification code."]);
        }
    } elseif (isset($request['email']) && isset($request['newPassword'])) {
        // Create new password logic
        $email = sanitizeInput($request['email']);
        $newPassword = password_hash(sanitizeInput($request['newPassword']), PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $newPassword, $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(["message" => "Password updated successfully."]);
        } else {
            echo json_encode(["error" => "Failed to update password."]);
        }
    }
}
?>
