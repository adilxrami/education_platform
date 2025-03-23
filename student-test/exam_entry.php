<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Dashboard</title>
    <link rel="stylesheet" href="css/exam_entry.css">
<style>
    .logout1{
        position: relative;
        bottom: 10%;
    }
.icon1{
position: relative;
bottom: 20%;
    }
.icon2{
    position: absolute;
    top: 20%;
}
</style>
</head>
<body>
    <div class="quiz-dashboard">
        <aside class="sidebar">
            <div class="header">Quiz Time</div>
            <ul>
                <li class="active"><span>Dashboard</span></li>
                <li> <span>Feedback</span></li>
                <li> <span>Notification</span></li>
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
                <h2>History Quiz</h2>
                <p>Answer the question below</p>
                <div class="quiz-content">
                    <img src="https://via.placeholder.com/150" alt="Quiz Image" class="quiz-image">
                    <div class="question">
                        <h4>Question 1/30</h4>
                        <p>
                            Guy Bailey, Roy Hackett and Paul Stephenson made history in 1963, as part of a protest against a bus company that refused to employ black and Asian drivers in which UK city?
                        </p>
                    </div>
                </div>
                <div class="options">
                    <label><input type="radio" name="answer" value="London"> London</label>
                    <label><input type="radio" name="answer" value="Edinburgh"> Edinburgh</label>
                    <label><input type="radio" name="answer" value="Liverpool"> Liverpool</label>
                    <label><input type="radio" name="answer" value="Canary Wharf"> Canary Wharf</label>
                </div>
                <button class="submit-btn">Select</button>
            </div>
        </main>
    </div>
    <script src="js/jquery-3.7.1.min.js"></script>
</body>
</html>
