<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="Macros.js"></script>
</head>
<body>

<?php
    // Start the session
    session_start();
    $_SESSION['user_id'];
$_SESSION['username'];
$_SESSION['email'];
$_SESSION['height'];
$_SESSION['weight'];
$_SESSION['age'];
$_SESSION['gender'];
$_SESSION['activity_level'];
$_SESSION['goal'];
$_SESSION['password'];
    // Check if session variables are set
    if(isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
        $user_id = $_SESSION['user_id'];
        $username = $_SESSION['username'];

        // Print user ID and username
        echo "<h1>Welcome, $username!</h1>";
        echo "<p>Your User ID is: $user_id</p>";
    } else {
        // Redirect to another page or display an error message
        header('Location: error.php');
        exit(); // Stop further execution
    }
?>

<nav class="navbar navbar-default">
    <div class="container-fluid" id="mainDiv">
        <div class="navbar-header" id="websitename">
            <a class="navbar-brand" href="#" id="brand">Fueler</a>
        </div>
        <ul class="nav navbar-nav">
            <li><a class="aa" href="#">Progress</a></li>
            <li><a class="aa" href="#">Profile</a></li>
            <li><a class="aa" href="#">Contact us</a></li>
            <li><a class="aa" href="#">About us</a></li>
        </ul>
    </div>
</nav>

<div class="bar">
    <div class="out">
        <div class="in">
            <div id="number">
                90%
            </div>
        </div>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="360px" height="360px">
        <defs>
            <linearGradient id="GradientColor">
                <stop offset="0%" stop-color="rgb(128, 53, 27)" />
                <stop offset="100%" stop-color="rgb(230, 107, 62)" />
            </linearGradient>
        </defs>
        <circle cx="105" cy="100" r="90" stroke-linecap="round" id="cir" class="animatedCircle" />
    </svg>
</div>

<script>
    let number = document.getElementById("number");
    let counter = 0;
    setInterval(() => {
        if(counter==90){
            clearInterval();
        } else {
            counter+=1;
            number.innerHTML=counter + "%";
        }
    }, 22);
</script>

<button onclick="bu()">hi</button>

</body>
</html>
