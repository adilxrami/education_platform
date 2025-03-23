
    // // Use the current question number as the key
    // const questionKey = "question_<?php echo $current_question; ?>";

    // // Save the selected answer to local storage
    // function saveAnswer(event) {
    //     localStorage.setItem(questionKey, event.target.value);
    // }

    // // Restore the saved answer on page load
    // function restoreAnswer() {
    //     const savedAnswer = localStorage.getItem(questionKey);
    //     if (savedAnswer) {
    //         const radioButton = document.querySelector(`input[name="answer"][value="${savedAnswer}"]`);
    //         if (radioButton) {
    //             radioButton.checked = true;
    //         }
    //     }
    // }

    // // Add event listeners to save the answer when a radio button is clicked
    // const radioButtons = document.querySelectorAll('input[name="answer"]');
    // radioButtons.forEach(radio => {
    //     radio.addEventListener('click', saveAnswer);
    // });

    // // Restore the saved answer when the page loads
    // window.addEventListener('DOMContentLoaded', restoreAnswer);
// Store answers in an object, with the current question as the key
