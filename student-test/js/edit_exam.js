/*edit exam*/

   // This should be initialized with the current number of questions

    // Function to dynamically add a question for editing
    $('#add-question').on('click', function () {
        let questionCount = 0;
        questionCount++;
        const questionHTML = `
        <div class="question-item" data-question-id="${questionCount}">
            <label for="question-text-${questionCount}">Question Text:</label>
            <textarea id="question-text-${questionCount}" name="question-text-${questionCount}" required placeholder="Enter the question"></textarea>

            <label for="question-type-${questionCount}">Question Type:</label>
            <select id="question-type-${questionCount}" class="question-type" data-question-id="${questionCount}" required>
                <option value="multiple-choice">Multiple Choice</option>
                <option value="true-false">True/False</option>
            
            </select>

            <div id="options-container-${questionCount}" class="options-container"></div>

            <button type="button" class="remove-question" data-question-id="${questionCount}"><img src="picture/delete.png"><span>Remove Question</span></button>
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
        } 
    });

    // Load existing questions for editing
    function loadExistingQuestions(questions) {
        questions.forEach((question, index) => {
            questionCount = index + 1;
            const questionHTML = `
            <div class="question-item" data-question-id="${questionCount}">
                <label for="question-text-${questionCount}">Question Text:</label>
                <textarea id="question-text-${questionCount}" name="question-text-${questionCount}" required>${question.text}</textarea>

                <label for="question-type-${questionCount}">Question Type:</label>
                <select id="question-type-${questionCount}" class="question-type" data-question-id="${questionCount}" required>
                    <option value="multiple-choice" ${question.type === 'multiple-choice' ? 'selected' : ''}>Multiple Choice</option>
                    <option value="true-false" ${question.type === 'true-false' ? 'selected' : ''}>True/False</option>
                  
                </select>

                <div id="options-container-${questionCount}" class="options-container">
                    ${generateOptionsHTML(questionCount, question.type, question.options)}
                </div>

                <button type="button" class="remove-question" data-question-id="${questionCount}"><img src="picture/delete.png"><span>Remove Question</span></button>
            </div>`;

            $('#questions-container').append(questionHTML);
        });
    }

    function generateOptionsHTML(questionId, type, options) {
        let optionsHTML = '';
        if (type === 'multiple-choice') {
            options.forEach((option, index) => {
                optionsHTML += `
                    <label>Option ${index + 1}:</label>
                    <input type="text" name="option-${questionId}-${index + 1}" value="${option}" required placeholder="Enter option ${index + 1}">
                `;
            });
        } else if (type === 'true-false') {
            optionsHTML += `
                <label>Correct Answer:</label>
                <select name="answer-${questionId}" required>
                    <option value="true" ${options === 'true' ? 'selected' : ''}>True</option>
                    <option value="false" ${options === 'false' ? 'selected' : ''}>False</option>
                </select>
            `;
        } else if (type === 'open-text') {
            optionsHTML += `
                <label>Expected Answer:</label>
                <textarea name="answer-${questionId}" placeholder="Enter expected answer (optional)">${options}</textarea>
            `;
        }
        return optionsHTML;
    }

    // Example usage: Load existing questions into the form
    const existingQuestions = [
        { text: "Sample question 1", type: "multiple-choice", options: ["Option 1", "Option 2", "Option 3", "Option 4"] },
        { text: "Sample question 2", type: "true-false", options: "true" },
        { text: "Sample question 3", type: "open-text", options: "Expected answer here" }
    ];
    loadExistingQuestions(existingQuestions);
/* end of edit exam*/