<?php
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
    s.university_number AS student_number, 
    s.student_name, 
    s.student_phone,  -- Fetching student phone
    s.student_department AS department,  
    s.student_gender AS gender,  -- Fetching gender from students table
    g.student_grade  -- Fetch student grade from grades table
FROM students s
LEFT JOIN grades g ON s.university_number = g.university_number 
                   AND s.student_department = g.department  -- Match both university number & department
ORDER BY s.student_name ASC";




$result = $connection->query($sql);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $original_student_number = $_POST["original_student_number"]; 
    $student_number = $_POST["student_number"];
    $student_name = $_POST["student_name"];
    $department = $_POST["department"];
    $gender = $_POST["gender"];
    $student_phone = $_POST["student_phone"];

    $sql = "UPDATE students 
            SET university_number=?, student_name=?, student_department=?, student_gender=?, student_phone=? 
            WHERE university_number=?";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssssss", $student_number, $student_name, $department, $gender, $student_phone, $original_student_number);

    if ($stmt->execute()) {
        echo "Student information updated successfully!";
    } else {
        echo "Error updating record: " . $connection->error;
    }

    $stmt->close();
    $connection->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/student_edit.css"/>
    <link rel="stylesheet" href="css/loader.css">
    <style>
    button img{
        width: 40px;
        height: 40px;
    }
    </style>

    <style>
    
        
    </style>
      <link rel="stylesheet" href="css/loader.css">
     <!-- Move script to the bottom for better performance -->
<script src="js/loader.js"></script>
        
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
            <li><a href="dashboard2.php">Dashboard</a></li>
            <li><a href="exam.php">Exams</a></li>
            <li><a href="professor_edit.php">professor_edit</a></li>
            <li><a href="student_edit.php">student_edit</a></li>
            <li><a href="admin_edit.php">admin_edit</a></li>
            <li><a href="logout.php">Sign out</a></li>
    
        </ul>
    </div>
    <div class="main-content">
    <div class="header">
        <h2>student</h2>
        <button id="createExam" onclick="myfunction()">+ add student</button>
    </div>

    <table class="table">
    <thead>
        <tr>
            <th>Student Name</th>
            <th>Student Number</th>
            <th>Department</th>
            <th>Gender</th>
            <th>Phone Number</th>  <!-- Corrected position -->
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($result->num_rows > 0) {
        // Loop through each record
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['student_name']}</td>
                    <td>{$row['student_number']}</td>
                    <td>{$row['department']}</td>  <!-- Department from students table -->
                    <td>{$row['gender']}</td>  <!-- Gender from students table -->
                    <td>{$row['student_phone']}</td>  <!-- Phone Number in correct place -->
                    <td>
                        <form id='editForm'>
                            <button class='edit-btn' type='button'><img src='picture/edit.png'></button>
                            <button class='delete-btn' type='button'><img src='picture/delete.png'></button>
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
        <h3>Edit Student</h3>
        <form id="editForm">
            <label for="student_name">Student Name:</label>
            <input type="text" id="student_name" name="student_name"><br><br>

            <label for="student_number">Student Number:</label>
            <input type="text" id="student_number" name="student_number"><br><br>

            <label for="department">Department:</label>
            <input type="text" id="department" name="department"><br><br>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select><br><br>

            <label for="student_phone">Phone Number:</label>
            <input type="text" id="student_phone" name="student_phone"><br><br>

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
        function myfunction(){
            location.href = "signup.php";
        }
    </script>

<script>
    function openEditPopup(student_number, student_name, department, gender, phone) {
        document.getElementById("original_student_number").value = student_number;
        document.getElementById("student_number").value = student_number;
        document.getElementById("student_name").value = student_name;
        document.getElementById("department").value = department;
        document.getElementById("gender").value = gender;
        document.getElementById("student_phone").value = phone;
        document.getElementById("popup").style.display = "block";
    }

    function closePopup() {
        document.getElementById("popup").style.display = "none";
    }

    document.getElementById("saveBtn").addEventListener("click", function () {
        let formData = new FormData(document.getElementById("editForm"));

        fetch("student_edit.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            
            location.reload(); // Refresh the page to show updated data
        })
        .catch(error => console.error("Error:", error));
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
    <!-- Move script to the bottom for better performance -->
<script src="js/loader.js"></script>
</body>
</html>
