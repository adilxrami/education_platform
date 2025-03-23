<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Exam</title>
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/exam.css">
    <style>
          button img{
            width: 20px;
            height: 20px;
        }
        button span{
            margin: 2px;
            padding: 2px;
            position:relative;
            bottom: 5px;
            font-weight: bold;
        }
    </style>
    <script src="js/jquery-3.7.1.min.js"></script>
</head>
<body>
    <h2 class="text-center">Edit Exam</h2>
    <form id="exam-edit-form">
        <label for="exam-id">Exam ID:</label>
        <input type="text" id="exam-id" name="exam-id" placeholder="Enter exam ID" required>

        <label for="exam-title">Exam Title:</label>
        <input type="text" id="exam-title" name="exam-title" placeholder="Enter new exam title" required>

        <label for="exam-duration">Exam Duration (minutes):</label>
        <input type="number" id="exam-duration" name="exam-duration" min="1" placeholder="Enter new duration" required>

        <label for="department">Department:</label>
        <select id="department" name="department" required>
            <option value="">--Select Department--</option>
            <option value="IS">Information System</option>
            <option value="IT">Information Technology</option>
            <option value="CS">Computer Science</option>
        </select>

        <h3>Edit Questions:</h3>
        <div id="questions-container"></div>

        <button type="button" id="add-question"><img src="picture/edit.png"><span>edit Question</span></button>
        <button type="submit">Save Changes</button>
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

    <script>
        $(document).ready(function () {
            let questionCount = 0;

            // Function to dynamically add question
            $('#add-question').on('click', function () {
                questionCount++;
                const questionHTML = `
                <div class="question-item" data-question-id="${questionCount}">
                    <label for="question-text-${questionCount}">Question Text:</label>
                    <textarea id="question-text-${questionCount}" name="question-text-${questionCount}" required placeholder="Enter the question"></textarea>

                    <label for="question-type-${questionCount}">Question Type:</label>
                    <select id="question-type-${questionCount}" class="question-type" data-question-id="${questionCount}" required>
                        <option value="multiple-choice">Multiple Choice</option>
                        <option value="true-false">True/False</option>
                        <option value="open-text">Open Text</option>
                    </select>

                    <div id="options-container-${questionCount}" class="options-container"></div>
                    
                    <button type="button" class="remove-question" data-question-id="${questionCount}"><img src='picture/delete.png' alt="Remove Question"> Remove Question</button>
                </div>`;

                $('#questions-container').append(questionHTML);
            });

            // Handle question type change to dynamically load options
            $('#questions-container').on('change', '.question-type', function () {
                const questionId = $(this).data('question-id');
                const questionType = $(this).val();
                const optionsContainer = $(`#options-container-${questionId}`);
                optionsContainer.empty();

                if (questionType === 'multiple-choice') {
                    optionsContainer.append(`
                        <label>Option 1:</label>
                        <input type="text" name="option-${questionId}-1" required placeholder="Enter option 1">
                        <label>Option 2:</label>
                        <input type="text" name="option-${questionId}-2" required placeholder="Enter option 2">
                        <label>Option 3:</label>
                        <input type="text" name="option-${questionId}-3" required placeholder="Enter option 3">
                        <label>Option 4:</label>
                        <input type="text" name="option-${questionId}-4" required placeholder="Enter option 4">
                    `);
                } else if (questionType === 'true-false') {
                    optionsContainer.append(`
                        <label>Correct Answer:</label>
                        <select name="answer-${questionId}" required>
                            <option value="true">True</option>
                            <option value="false">False</option>
                        </select>
                    `);
                } else if (questionType === 'open-text') {
                    optionsContainer.append(`
                        <label>Expected Answer:</label>
                        <textarea name="answer-${questionId}" placeholder="Enter expected answer (optional)"></textarea>
                    `);
                }
            });

            // Handle question removal
            $('#questions-container').on('click', '.remove-question', function () {
                const questionId = $(this).data('question-id');
                $(`.question-item[data-question-id="${questionId}"]`).remove();
            });

            // Form submission
            $('#exam-edit-form').on('submit', function (e) {
                e.preventDefault();
                // Collect and process form data here
                alert('Exam changes saved successfully!');
            });
        });
    </script>
    <script src="js/edit_exam.js"></script>
</body>
</html>


 
