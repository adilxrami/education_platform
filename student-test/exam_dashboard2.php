<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Dashboard</title>
    <link rel="stylesheet" href="css/exam_entry.css">
    <link rel="stylesheet" href="css/exam_dashboard.css">
<style>
    .logout1{
        position: relative;
        bottom: 10%;
    }
    
</style>
</head>
<body>
    <div class="quiz-dashboard">
        <aside class="sidebar">
            <div class="header">Quiz Time</div>
            <ul>
                <li class="active"> Dashboard</li>
                <li>Feedback</li>
                <li>Notification</li>
            </ul>
            <div class="logout"><div><img class="icon logout1" src="picture/logout.png" width="20px" height="20px"></div>Log Out</div>
        </aside>
        <main>
            <div class="top-bar">
                <input type="text" placeholder="Search...">
                <button class="start-quiz">Start Quiz</button>
                <div class="user-profile">
                    <span class="profile-icon">M</span>
                    <span class="username">malaz</span>
                </div>
            </div>
            <div class="quiz-section">
            
                <button class="submit-btn">Select</button>
            </div>
        </main>
    </div>
    <script src="js/jquery-3.7.1.min.js"></script>
</body>
</html>
