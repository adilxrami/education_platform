<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success Box</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .success-box {
            width: 100px;
            height: 100px;
            background-color: #1d9ea6;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 50px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: scale(0.5);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        .success-box.active {
            opacity: 1;
            transform: scale(1);
            animation: bounce 0.5s ease;
        }
        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        .message {
            margin-top: 20px;
            font-size: 20px;
            color: #1d9ea6;
            font-weight: bold;
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        .message.active {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="success-box" id="successBox">✔</div>
    <div class="message" id="successMessage">You have successfully submitted the exam</div>
    <script>
        function showSuccessBox() {
            const box = document.getElementById("successBox");
            const message = document.getElementById("successMessage");
            box.classList.add("active");
            setTimeout(() => message.classList.add("active"), 500);
        }
        setTimeout(showSuccessBox, 1000);
        
        // Redirect to another page after 5 seconds
        setTimeout(() => {
           location.href = "index.php"; // Change URL as needed
        }, 5000);
    </script>
</body>
</html>
