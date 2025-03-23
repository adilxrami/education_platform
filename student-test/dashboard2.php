<?php
require_once 'reterieve_student.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard2.css">
    <link rel="stylesheet" href="css/loader.css">
    <!-- Move script to the bottom for better performance -->
<script src="js/loader.js"></script>
<style>li a {
text-decoration :none;
color: #fff;
}</style>
</head>
<body>
    
<div class="loader">
  <div class="load-inner load-one"></div>
  <div class="load-inner load-two"></div>
  <div class="load-inner load-three"></div>
  <span class="loader-text">Loading</span>
</div>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="logo">Logo</div>
            <nav>
            <ul>
            <li><a class ="active" href="dashboard2.php">Dashboard</a></li>
            <li><a href="exam.php">Exams</a></li>
            <li><a href="professor_edit.php">professor_edit</a></li>
            <li><a href="student_edit.php">student_edit</a></li>
            <li><a href="admin_edit.php">admin_edit</a></li>
            <li><a href="logout.php">Sign out</a></li>
    
        </ul>
            </nav>
        
        </aside>
        <main>
            <div class="header">
                <h2>Dashboard</h2>
                <p>Welcome back, Jo</p>
                <div class="sort-dropdown">
                    <label for="sort">Sort:</label>
                    <select id="sort">
                        <option>Last Week</option>
                        <option>Last Month</option>
                    </select>
                </div>
                <div class="notification">🔔</div>
                <div class="profile">J</div>
            </div>
            <div class="stats">
                <div class="card">
                    <h3>Exams</h3>
                    <p class="value"><?php echo "" . $exam_count;?></p>
                    <div class="chart blue-gradient"></div>
                </div>
                <div class="card">
                    <h3>Students</h3>
                    <p class="value"><?php echo "" . $student_count?></p>
                    <div class="chart red-gradient"></div>
                </div>
                <div class="card">
                    <h3>admins</h3>
                    <p class="value"><?php echo "" . $admin_count ;?></p>
                    <div class="chart blue-gradient"></div>
                </div>
                <div class="card">
                    <h3>professors</h3>
                    <p class="value"><?php echo "" . $professor_count ;?></p>
                    <div class="chart blue-gradient"></div>
                </div>
            </div>
        </main>
    </div>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function () {
    $(".sidebar nav ul li").on("click", function () {
        $(".sidebar nav ul li").removeClass("active");
        $(this).addClass("active");
    });


});
    </script>

</body>
</html>
