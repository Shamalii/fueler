<html>
<head>
<meta charset="UTF-8">
</head>
<body>


<?php
// Start the session
session_start();

//connect with server 
$connect=mysqli_connect("localhost", "root", "12345678");

//select database (fitnut) 
mysqli_select_db($connect, "fitnut");


// Check the connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}


// get user's information from html page (profile) using (select_list)

$activity_level = $_POST['activity_level'];
$goal = $_POST['goal'];

//Retrieve user ID from the session
 $_SESSION['user_id'];


   // Store user information in session variables
    $_SESSION['activity_level'] = $activity_level;
	$_SESSION['goal'] = $goal;
 
 
 
 
 
 
 
header('Location: allergy.html');


//close database connection
mysqli_close($connect);
?>
   
</body>
</html>