<html>
<head></head>
<body>
<?php

//connect with server 
$connect=mysqli_connect( "localhost" ,"root", "12345678");

//select database (fitnut)
$loginform=mysqli_select_db($connect,"fitnut");

// get user's information from html page (login_form) using (form)
 $user_name=$_POST["username_2"];
 $user_email= $_POST["email_2"];
 $user_password=$_POST["password_2"];
 $newpassword=$_POST["password_3"];
 
//get user's information from database
 $check = "select username , password1 from signup where username = '$user_name' and password1 = '$user_password'";
 $result= mysqli_query($connect , $check);
 //statement to change user's password 
 

 //check if the username and password are in database 
if (mysqli_num_rows($result) > 0 ){
	//statement to change user's password after check if his name and password are in database
        //$newpsw="update sign_up set password = '$newpassword' where username='$u_name'" ; 
		$update=mysqli_query($connect , "update signup set password1 = '$newpassword' where username='$user_name' and email='$user_email'");
		   print("<h1 style = 'color:blue ; font-size:90px ; text-alien:center' >Successfully Updated</h1>");
		   
}		   
else 
print("<h2 style = 'color:red ; font-size:90px ; text-alien:center'>Please check your name , password or email  <h2>");
 
 
 
  mysqli_close($connect);
  


?>
</body >
</html >