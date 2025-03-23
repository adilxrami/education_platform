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
    throw new Exception("Connection failed: " . $connection->connect_error);
}

$sql = "
SELECT 
    e.exam_title AS exam_name, 
    s.university_number AS student_number, 
    s.student_name, 
    s.student_department AS department,  -- Fetch department from students table
    g.student_subject,  -- Fetch subject from grades table
    g.student_grade,  -- Fetch student grade from grades table
    g.total_subject_mark,  -- Fetch total subject mark from grades table
    g.subject_date  -- Fetch subject date from grades table
FROM students s
LEFT JOIN student_exam e ON s.student_department = e.department  -- Join exams based on department
LEFT JOIN grades g ON s.university_number = g.university_number 
                   AND s.student_department = g.department  -- Match both university number & department
ORDER BY s.student_name ASC;
";






$result = $connection->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboard.css"/>
    <link rel="stylesheet" href="css/loader.css">
    <style>
    button img{
        width: 40px;
        height: 40px;
    }
    </style>

    <style>
    
        
    </style>
        
</head>
<body >
<div class="loader">
  <div class="load-inner load-one"></div>
  <div class="load-inner load-two"></div>
  <div class="load-inner load-three"></div>
  <span class="loader-text">Loading</span>
</div>
    <div class="sidebar">
        <div class="logo">Logo</div>
        <ul>
   
            <li><a href="exam.php">Exams</a></li>
            <li><a href="professor_student_edit.php">student_edit</a></li>
            <li><a href="logout.php">Sign out</a></li>
        
        </ul>
    </div>
    <div class="main-content">
    <div class="header">
        <h2>Exam</h2>
        <button id="createExam">+ Create Exam</button>
    </div>


<table class="table">
        <thead>
            <tr>
                <th>Exam Name</th>
                <th>Student Number</th>
                <th>Student Name</th>
                <th>department</th>
                <th>midterm</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['exam_name']}</td>
                    <td>
                        <form method='POST' action='monitor_student.php' class='student-form'>
                            <input type='hidden' name='student_number' value='{$row['student_number']}'>
                            <button type='submit' class='student-link'>{$row['student_number']}</button>
                        </form>
                    </td>
                    <td>{$row['student_name']}</td>
                    <td>{$row['department']}</td>
                    <td>{$row['student_grade']}</td>
                    <td>
                       <form method='POST' action='delete_record.php'>
                       
                            <input type='hidden' name='student_number' value='{$row['student_number']}'>
                                <input type='hidden' name='department' value='{$row['department']}'>
                                <button class='edit-btn' type='button'><img src='picture/edit.png'></button>
                                <button type='submit' class='delete-btn' type='button'><img src='picture/delete.png' class='delete-btn' onclick=\"return confirm('Are you sure you want to delete this record?')\">
                        
                                </button>
                            </form>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No records found</td></tr>";
    }
    ?>
</tbody>


    </table>
</div>

<div id="popup" class="popup">
    <div class="popup-content">
        <h3>Edit Row</h3>
        <form id="editForm">
            <label for="exam_name">Exam Name:</label>
            <input type="text" id="exam_name" name="exam_name"><br><br>

            <label for="student_number">Student Number:</label>
            <input type="text" id="student_number" name="student_number"><br><br>

            <label for="student_name">Student Name:</label>
            <input type="text" id="student_name" name="student_name"><br><br>

            <label for="department">Department:</label>
            <input type="text" id="department" name="department"><br><br>

            <label for="student_grade">Student Grade:</label>
            <input type="number" id="student_grade" name="student_grade"><br><br>

            <button type="button" id="saveBtn">Save</button>
            <button type="button" id="closeBtn">Close</button>
        </form>
    </div>
</div>

    <script src="js/jquery-3.7.1.min.js"></script>
    <script>
     document.getElementById("edit-btn").addEventListener("click", function (event) {
            event.preventDefault(); // Prevent the form from submitting
            console.log("Default action prevented!");
        });
        $(document).ready(function() {
            $('#createExam').on('click', function() {
                alert('Create Exam button clicked!');
            });

            $('.actions button').on('click', function() {
                const action = $(this).text();
                const examName = $(this).closest('tr').find('td:first').text();
                alert(`${action} action for ${examName}`);
            });
        });
    </script>
    <script>
        $(document).ready(function () {
    let selectedRow;

    // Open popup and populate fields
    $(".edit-btn").on("click", function () {
        selectedRow = $(this).closest("tr");
        $("#exam").val(selectedRow.find("td:eq(0)").text());
        $("#value").val(selectedRow.find("td:eq(1)").text());
        $("#question").val(selectedRow.find("td:eq(2)").text());
        $("#time").val(selectedRow.find("td:eq(3)").text());
        $("#popup").fadeIn();
    });

    // Save changes
    $("#saveBtn").on("click", function () {
        if (selectedRow) {
            selectedRow.find("td:eq(0)").text($("#exam").val());
            selectedRow.find("td:eq(1)").text($("#value").val());
            selectedRow.find("td:eq(2)").text($("#question").val());
            selectedRow.find("td:eq(3)").text($("#time").val());
        }
        $("#popup").fadeOut();
    });

    // Close popup
    $("#closeBtn").on("click", function () {
        $("#popup").fadeOut();
    });
});

    </script>
    <script>
    $(document).ready(function () {
        $(".clickable-row").on("click", function () {
            let studentNumber = $(this).data("student");
            window.location.href = "monitor_student.php?student_number=" + encodeURIComponent(studentNumber);
        });
    });
</script>

    <!-- Move script to the bottom for better performance -->
<script src="js/loader.js"></script>
</body>
</html>
