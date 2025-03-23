<?php
session_start();
header('Content-Type: application/json');

// Ensure session variables are set
if (!isset($_SESSION['start_time']) || !isset($_SESSION['exam_duration'])) {
    echo json_encode(['remainingTime' => 0]);
    exit;
}

// Calculate remaining time
$elapsedTime = time() - $_SESSION['start_time'];
$remainingTime = ($_SESSION['exam_duration'] * 60) - $elapsedTime;

// Prevent negative time
if ($remainingTime < 0) {
    $remainingTime = 0;
}

echo json_encode(['remainingTime' => $remainingTime]);
?>
