<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Form</title>
    <style>
        form {
            margin: 20px;
        }

        label, input, select, button {
            display: block;
            margin: 10px 0;
        }
        button img {
            width: 20px;
            height: 20px;
        }
        button span {
            margin: 2px;
            padding: 2px;
            position: relative;
            bottom: 5px;
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/exam.css">
</head>
<body>
    <h2 class="text-center">Create an Exam</h2>
    <form id="exam-change-form" method ="post">
        <label for="exam-change-type">Exam Change Type:</label>
        <select id="exam-change-type" name="exam-change-type" required>
            <option value="">--Select Exam Change Type--</option> 
            <option value="grade">Grade</option>
        </select>

        <div id="dynamic-fields"></div>
        <button type="submit">Submit Change</button>
    </form>

    <form id="exam-form" method="post" action="create_exam.php">
    <!-- Removed the exam ID field since it's auto-generated by the database -->

    <label for="exam-title">Exam Title:</label>
    <input type="text" id="exam-title" name="exam_title" placeholder="Enter exam title" required>
    
    <label for="exam_tips">Exam Tips:</label>
    <input type="text" id="exam_tips" name="exam_tips" required maxlength="1000">

    <label for="exam-duration">Exam Duration (minutes):</label>
    <input type="number" id="exam-duration" name="exam_duration" min="1" placeholder="Enter duration" required>
    
    <label for="department">Department:</label>
    <select id="change-department" name="department" required>
        <option value="">--Select department--</option>
        <option value="IS">Information System</option>
        <option value="IT">Information Technology</option>
        <option value="CS">Computer Science</option>
    </select>

    <h3>Questions:</h3>
    <div id="questions-container"></div>

    <button type="button" id="add-question">
        <img src="picture/add.png"><span>Add Question</span>
    </button>
    <button type="submit">Create Exam</button>
</form>

    <footer class="footer">
        <div class="footer-links">
            <a href="#">Home</a>
            <a href="#">About Us</a>
            <a href="#">Contact</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
        </div> 
    </footer>

    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        $(document).ready(function () {
            // Define dynamic fieldsets
            const fieldSets = {
                grade: `
                    <label for="student-id">Student ID:</label>
                    <input type="text" id="student-id" name="student-id" required>
                    <label for="department">Department:</label>
                    <select id="department" name="department" required>
                        <option value="">--Select department--</option>
                        <option value="IS">Information System</option>
                        <option value="IT">Information Technology</option>
                        <option value="CS">Computer Science</option>
                    </select>
                    <label for="student-subject">Student Subject:</label>
                    <input type="text" id="student-subject" name="student-subject" required>
                    <label for="grade">Student Grade:</label>
                    <input type="text" id="grade" name="grade" required min="0" max="100">
                    <label for="total-subject-mark">Total Subject Mark:</label>
                    <input type="text" name="total-subject-mark" required>

                    <label for="subject-date">Subject Date:</label>
                    <input type="date" id="subject-date" name="subject-date" required>
                `
            };

            // Change dynamic fields based on selected type
            $('#exam-change-type').on('change', function () {
                const selectedType = $(this).val();

                // Clear existing dynamic fields
                $('#dynamic-fields').empty();

                // Add new fields based on the selected exam change type
                if (fieldSets[selectedType]) {
                    $('#dynamic-fields').html(fieldSets[selectedType]);
                }
            });

            // Handle form submission for exam creation
            $("#exam-form").on("submit", function (e) {
                e.preventDefault();

                const formData = $(this).serialize();

                $.ajax({
                    url: "create_exam.php", // backend PHP file for creating exams
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        alert(response);
                        // Handle success - redirect or display message
                    },
                    error: function () {
                        alert("An error occurred while creating the exam.");
                    }
                });
            });

            // Handle form submission for exam change (Grade change)
            $("#exam-change-form").on("submit", function (e) {
                e.preventDefault();

                const formData = $(this).serialize();

                $.ajax({
                    url: "exam_change.php", // backend PHP file for handling grade change
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        alert(response);
                        // Handle success - redirect or display message
                    },
                    error: function () {
                        alert("An error occurred while processing the change.");
                    }
                });
            });
        });
    </script>
    <script src="js/exam.js"></script>
</body>
</html>
