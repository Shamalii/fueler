<html>
<head></head>
<body>
<?php

//connect with server  done 
$connect=mysqli_connect( "localhost" ,"root", "12345678");

//select database (login)
$loginform=mysqli_select_db($connect,"fitnut");

// get user's information from html page (login_form) using (form)
 $user_name=$_POST["username_2"];
 $user_password=$_POST["password_2"];
 
//get user's information from database
 $check_information = "select username , password from signup where username = '$user_name' and password = '$user_password'";
 $result= mysqli_query($connect , $check_information);

 //check if the username and password are match in database 
if ($result && mysqli_num_rows($result) >0 ){
 header('location:Main.html'); // file_name.html
exit;
 }                
else 
print("<h2 style = 'color:red ; font-size:90px ; text-alien:center'> Wrong Username or Password <h2>");
 
 
 mysqli_close($connect);
  


?>
</body >
</html >