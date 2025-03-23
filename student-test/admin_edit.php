<?php
// Database connection details
$host = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "university_exam"; 

// Create connection
$connection = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($connection->connect_error) {
    throw new Exception("Connection failed: " . $connection->connect_error);
}

$sql = "
SELECT 
    a.id AS admin_id, 
    a.name AS admin_name, 
    a.phone AS admin_phone,  
    a.email AS admin_email
FROM admins a
ORDER BY a.name ASC";

$result = $connection->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_POST["admin_id"]; 
    $admin_name = $_POST["admin_name"];
    $admin_phone = $_POST["admin_phone"];
    $admin_email = $_POST["admin_email"];

    $sql = "UPDATE admins 
            SET name=?, phone=?, email=? 
            WHERE id=?";

    // Use `$connection` instead of `$conn`
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sssi", $admin_name, $admin_phone, $admin_email, $admin_id);
    if ($stmt->execute()) {
        header("Location: ".$_SERVER['PHP_SELF']); // Redirect to the same page
        exit(); // Prevent further script execution
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
            <li><a href="exam_dashboard.php">Exams</a></li>
            <li><a href="student_edit.php">student_edit</a></li>
            <li><a href="admin_edit.php">admin_edit</a></li>
            <li><a href="logout.php">Sign out</a></li>
        </ul>
    </div>
    <div class="main-content">
    <div class="header">
        <h2>student</h2>
        <button id="createExam" onclick="myfunction()">+ add admin</button>
    </div>
    <table class="table">
    <thead>
        <tr>
            <th>Admin Name</th>
            <th>Admin ID</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($result->num_rows > 0) {
        // Loop through each record
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['admin_name']}</td>
                    <td>{$row['admin_id']}</td>
                    <td>{$row['admin_phone']}</td>  <!-- Admin Phone -->
                    <td>{$row['admin_email']}</td>  <!-- Admin Email -->
                    <td>
                        <button class='edit-btn' type='button' onclick='openEditPopup({$row["admin_id"]}, \"{$row["admin_name"]}\", \"{$row["admin_phone"]}\", \"{$row["admin_email"]}\")'>
                            <img src='picture/edit.png'>
                        </button>
                        <button class='delete-btn' type='button'><img src='picture/delete.png'></button>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No records found</td></tr>";
    }
    ?>
    </tbody>
</table>

<!-- Edit Admin Popup -->
<div id="popup" class="popup" style="display: none;">
    <div class="popup-content">
        <h3>Edit Admin</h3>
        <form id="editForm">
            <input type="hidden" id="admin_id" name="admin_id"> <!-- Hidden field for ID -->

            <label for="admin_name">Admin Name:</label>
            <input type="text" id="admin_name" name="admin_name"><br><br>

            <label for="admin_phone">Phone Number:</label>
            <input type="text" id="admin_phone" name="admin_phone"><br><br>

            <label for="admin_email">Email:</label>
            <input type="email" id="admin_email" name="admin_email"><br><br>

            <button type="button" id="saveBtn">Save</button>
            <button type="button" id="closeBtn" onclick="closePopup()">Close</button>
        </form>
    </div>
</div>

<script>
    function openEditPopup(id, name, phone, email) {
        document.getElementById("admin_id").value = id;
        document.getElementById("admin_name").value = name;
        document.getElementById("admin_phone").value = phone;
        document.getElementById("admin_email").value = email;
        document.getElementById("popup").style.display = "block";
    }

    function closePopup() {
        document.getElementById("popup").style.display = "none";
    }

    document.getElementById("saveBtn").addEventListener("click", function () {
        let formData = new FormData(document.getElementById("editForm"));

        fetch("admin_edit.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            location.reload(); // Refresh the page to show updated data
        })
        .catch(error => console.error("Error:", error));
    });
    function myfunction(){
            location.href = "signup.php";
        }
</script>
    
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
    <!-- Move script to the bottom for better performance -->
<script src="js/loader.js"></script>
</body>
</html>
