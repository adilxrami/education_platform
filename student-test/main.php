<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="css/main.css"/>
    <link rel="stylesheet" href="css/footer.css">
    <title>Centered Container</title>
    <style>
        .header .logo {
            position: absolute;
            top: 0%;
            background-image: url('picture/logo.png');
            background-size: cover;
            width: 60px;
            height: 50px;
            border-radius: 10px;
        }
        .header div p{
            float: left;
            color:#50B4C8;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
        
        </div>
        <ul>
            <li><a href="main.php"> Home </a></li>
            <li><a href="index.php">Login</a></li>
            <li><a href="signup.hphptml" class="decorate">Get Started</a></li>
        </ul>
    </div>
    <div class="container">
        <div class="box"><h1>an electronic Examination platform</h1>
        <p>NUMBER OF ACTIVE student RIGHT NOW</p>
        <h3>200+</h3>
        </div>
        <div class="box student"></div>
    </div>
    <div class="section">    
        <div class="box pix_1">
            <div class=" pix1 text-center"></div>
            <h6 class="text-center">student exam<h6>
        </div>
        <div class="box pix_2">
            <div class=" pix2 text-center"></div>
            <h6 style="color: azure;" class="text-center">instructor<h6>
        </div>    
        <div class="box pix_3">
            <div class=" pix3 text-center"></div>
            <h6 class="text-center">university admin<h6>
        </div>
    </div>
    <div class="article">
        <div class="box"></div>
        <div class="box"></div>
        <div class="box"></div>
        <div class="box"></div>
    </div>
    <footer class="footer">
        <div class="footer-links">
            <a href="#">Home</a>
            <a href="#">About Us</a>
            <a href="#">Contact</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
        </div>
        <p>&copy; 2025 Exam Website. All Rights Reserved.</p>
    </footer>
</body>
</html>
