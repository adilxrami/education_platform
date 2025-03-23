<?php
// Check if the request is a POST request and contains the necessary data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question']) && isset($_POST['answer'])) {
    $questionNumber = $_POST['question'];
    $answer = $_POST['answer'];

    // Here, you can save the answer to a session, database, or file
    session_start();  // Start session if you're saving it in session
    $_SESSION['answers'][$questionNumber] = $answer;

    // Optionally, send a response back to the client
    echo json_encode(["status" => "success", "message" => "Answer saved!"]);
}
?>
