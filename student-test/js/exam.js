$(document).ready(function () {
    let questionCount = 0;

    // Function to add a question dynamically
    $("#add-question").on("click", function () {
        questionCount++;

        const questionHTML = `
            <fieldset id="question-${questionCount}">
                <legend>Question ${questionCount}</legend>
                <label for="question-${questionCount}-text">Question Text:</label>
                <input type="text" id="question-${questionCount}-text" name="questions[${questionCount}][text]" placeholder="Enter question text" required>
                
                <label for="question-${questionCount}-type">Question Type:</label>
                <select id="question-${questionCount}-type" name="questions[${questionCount}][type]" required>
                    <option value="" > Choice</option>
                    <option value="multiple-choice">Multiple Choice</option>
                    <option value="true-false">True/False</option>
                </select>

                <div id="question-${questionCount}-options">
                    <!-- Options will be dynamically added here -->
                </div>

  
                <div id="question-${questionCount}-correct-answer">
                    <!-- Correct answer select will be dynamically added here -->
                </div>

                <button type="button" class="remove-question" onclick="removeQuestion(${questionCount})">Remove Question</button>
            </fieldset>
        `;

        $("#questions-container").append(questionHTML);

        // Add options and correct answer field based on question type
        $(`#question-${questionCount}-type`).on('change', function () {
            const questionType = $(this).val();
            const optionsContainer = $(`#question-${questionCount}-options`);
            const correctAnswerContainer = $(`#question-${questionCount}-correct-answer`);

            if (questionType === 'multiple-choice') {
                optionsContainer.html(`
                    <label for="question-${questionCount}-option-A">Option A:</label>
                    <input type="text" id="question-${questionCount}-option-A" name="questions[${questionCount}][options][A]" placeholder="Option A" required>
                    <label for="question-${questionCount}-option-B">Option B:</label>
                    <input type="text" id="question-${questionCount}-option-B" name="questions[${questionCount}][options][B]" placeholder="Option B" required>
                    <label for="question-${questionCount}-option-C">Option C:</label>
                    <input type="text" id="question-${questionCount}-option-C" name="questions[${questionCount}][options][C]" placeholder="Option C">
                    <label for="question-${questionCount}-option-D">Option D:</label>
                    <input type="text" id="question-${questionCount}-option-D" name="questions[${questionCount}][options][D]" placeholder="Option D">
                `);

                correctAnswerContainer.html(`
                    <label for="question-${questionCount}-correct-answer">Correct Answer:</label>
                    <select name="questions[${questionCount}][correct_answer]" required>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                `);
            } else if (questionType === 'true-false') {
                optionsContainer.html('');
                correctAnswerContainer.html(`
                    <label for="question-${questionCount}-correct-answer">Correct Answer:</label>
                    <select name="questions[${questionCount}][correct_answer]" required>
                        <option value="true">True</option>
                        <option value="false">False</option>
                    </select>
                `);
            }
        });
    });

    // Remove question dynamically
    window.removeQuestion = function (questionId) {
        $(`#question-${questionId}`).remove();
    };
});
