<?php

//connect with server  done 
$connect=mysqli_connect( "localhost" ,"root", "12345678");

//select database (login)
$loginform=mysqli_select_db($connect,"fitnut");

// get user's information from html page (login_form) using (form)
 $admin_name=$_POST["name"];
 $admin_password=$_POST["password"];
 
//get user's information from database
 $check_information = "select name, password from admin where name = '$admin_name' and password = '$admin_password'";
 $result= mysqli_query($connect , $check_information);

 //check if the user email and password are match in database 
if ($result && mysqli_num_rows($result) >0 ){
 header('location:admin.html'); // file_name.html
exit;
 }      
 
else {
	
// print("<h2 style = 'color:red ; font-size:90px ; text-alien:center'> Wrong Name or Password <h2>");
    exit;
}

 mysqli_close($connect);
 
?>