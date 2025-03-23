<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/loader.css">
    <style>
        #email_link {
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
    <style>
    a{
        text-decoration:none;padding:10px;
      position: relative;
      bottom:10%;
      font-size:12px;
    }
</style>
        <script src="js/loader.js"></script>
</head>
<body>
<div class="loader">
  <div class="load-inner load-one"></div>
  <div class="load-inner load-two"></div>
  <div class="load-inner load-three"></div>
  <span class="loader-text">Loading</span>
</div>
    <div class="box">
        <div class="text-center">
            <img src="picture/university.png" alt="University Logo">
        </div>
        <h3 class="text-center">welcome to sign in </h3>
        <form id="login-form" action="login-handler.php" method="POST">
            <label for="login-user-type">User Type:</label>
            <select id="login-user-type" name="login_user_type" required>
                <option value="">--Select User Type--</option>
                <option value="student">Student</option>
                <option value="professor">Professor</option>
                <option value="admin">University Administration</option>
            </select>

            <div id="dynamic-fields"></div>
            <a href="signup.php" target="_self">Sign Up Here</a>

            <button type="submit">Login</button>
        </form>
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
    <script>
        $(document).ready(function () {
            // Handle dynamic fields
            $("#login-user-type").on("change", function () {
                const userType = $(this).val();
                const fieldsContainer = $("#dynamic-fields");

                fieldsContainer.empty(); // Clear previous dynamic fields

                if (userType === "student") {
                    fieldsContainer.append(`
                        <label for="university_number">University Number:</label>
                        <input type="text" id="university_number" name="university_number" placeholder="Enter University Number" required>
                    `);
                } else if (userType === "professor" || userType === "admin") {
                    fieldsContainer.append(`
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    `);
                }

                fieldsContainer.append(`
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <a href="emailverify.php" id="email_link" class="forgot-password">Forgot your password?</a>
                `);
            });
        });
    </script>

</body>
</html>
