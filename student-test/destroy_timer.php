<?php
session_start();

// Unset timer session variables
unset($_SESSION['exam_duration']);
unset($_SESSION['start_time']);

// Send a JSON response
echo json_encode(["success" => true]);
?>
