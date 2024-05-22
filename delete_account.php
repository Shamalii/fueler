<html>
<head></head>
<body>
<?php

//connect with server 
$connect=mysqli_connect( "localhost" ,"root", "12345678");

//select database (fitnut)
$loginform=mysqli_select_db($connect,"fitnut");

// get user's information from html page (login_form) using (form)
 $user_name=$_POST["username_4"];
 $user_password=$_POST["password_4"];
 $user_email=$_POST["email_4"];
 
//get user's information from database
 $check = "select username , password from signup where username = '$user_name' and password = '$user_password' and email='$user_email' ";
 $result= mysqli_query($connect, $check);

 //check if the username and password are in database 
if (mysqli_num_rows($result) > 0 ){

$delete="delete from signup where username='$user_name' and password='$user_password'";   
mysqli_query($connect, $delete);   
print("<h2 style = 'color:blue ; font-size:90px ; text-alien:center'> Your account has been permanently deleted  <h2>") ;
}
        
else 
print("<h2 style = 'color:red ; font-size:90px ; text-alien:center'> Please check your <u>username or password <u> <h2>");
 
 
 
  mysqli_close($connect);
  


?>
</body >
</html >