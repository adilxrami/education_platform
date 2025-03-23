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
    p.id AS professor_id, 
    p.name AS professor_name, 
    p.phone AS professor_phone,  
    p.email AS professor_email
FROM professors p
ORDER BY p.name ASC";

$result = $connection->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $professor_id = $_POST["professor_id"]; 
    $professor_name = $_POST["professor_name"];
    $professor_phone = $_POST["professor_phone"];
    $professor_email = $_POST["professor_email"];

    $sql = "UPDATE professors 
            SET name=?, phone=?, email=? 
            WHERE id=?";

    // Use `$connection` instead of `$conn`
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sssi", $professor_name, $professor_phone, $professor_email, $professor_id);
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
    <title>Professor Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/student_edit.css"/>
    <link rel="stylesheet" href="css/loader.css">
    <style>
    button img {
        width: 40px;
        height: 40px;
    }
    </style>

    <link rel="stylesheet" href="css/loader.css">
    <script src="js/loader.js"></script>
</head>
<body>
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
            <h2>Professors</h2>
            <button id="createProfessor" onclick="myfunction()">+ Add Professor</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Professor Name</th>
                    <th>Professor ID</th>
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
                                <td>{$row['professor_name']}</td>
                                <td>{$row['professor_id']}</td>
                                <td>{$row['professor_phone']}</td>  
                                <td>{$row['professor_email']}</td>  
                                <td>
                                    <button class='edit-btn' type='button' onclick='openEditPopup({$row["professor_id"]}, \"{$row["professor_name"]}\", \"{$row["professor_phone"]}\", \"{$row["professor_email"]}\")'>
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

        <!-- Edit Professor Popup -->
        <div id="popup" class="popup" style="display: none;">
            <div class="popup-content">
                <h3>Edit Professor</h3>
                <form id="editForm">
                    <input type="hidden" id="professor_id" name="professor_id">

                    <label for="professor_name">Professor Name:</label>
                    <input type="text" id="professor_name" name="professor_name"><br><br>

                    <label for="professor_phone">Phone Number:</label>
                    <input type="text" id="professor_phone" name="professor_phone"><br><br>

                    <label for="professor_email">Email:</label>
                    <input type="email" id="professor_email" name="professor_email"><br><br>

                    <button type="button" id="saveBtn">Save</button>
                    <button type="button" id="closeBtn" onclick="closePopup()">Close</button>
                </form>
            </div>
        </div>

        <script>
            function openEditPopup(id, name, phone, email) {
                document.getElementById("professor_id").value = id;
                document.getElementById("professor_name").value = name;
                document.getElementById("professor_phone").value = phone;
                document.getElementById("professor_email").value = email;
                document.getElementById("popup").style.display = "block";
            }

            function closePopup() {
                document.getElementById("popup").style.display = "none";
            }

            document.getElementById("saveBtn").addEventListener("click", function () {
                let formData = new FormData(document.getElementById("editForm"));

                fetch("professor_edit.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    location.reload(); // Refresh the page to show updated data
                })
                .catch(error => console.error("Error:", error));
            });

            function myfunction() {
                location.href = "signup.php";
            }
        </script>

        <script src="js/jquery-3.7.1.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#createProfessor').on('click', function() {
                    alert('Create Professor button clicked!');
                });

                $('.actions button').on('click', function() {
                    const action = $(this).text();
                    const professorName = $(this).closest('tr').find('td:first').text();
                    alert(`${action} action for ${professorName}`);
                });
            });
        </script>

    </div>
</body>
</html>
