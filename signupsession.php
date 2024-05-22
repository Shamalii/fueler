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

// Get user's information from the HTML form (sanitize input)

/*
   mysqli_real_escape_string
This helps to prevent SQL injection attacks by ensuring that any special characters in the input data 
are properly handled and do not inadvertently alter the structure of the SQL query.

*/


$user_name = mysqli_real_escape_string($connect, $_POST["username_1"]);
//$user_password = mysqli_real_escape_string($connect, $_POST["password_1"]);
$user_password = $_POST["password_1"];
$user_email = mysqli_real_escape_string($connect, $_POST["email_1"]);
$user_age = mysqli_real_escape_string($connect, $_POST["age_1"]);
$user_gender = mysqli_real_escape_string($connect, $_POST["gender_1"]);
$user_height = mysqli_real_escape_string($connect, $_POST["height_1"]);
$user_weight = mysqli_real_escape_string($connect, $_POST["weight_1"]);

// Encrypt the password
$hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

// Check if the email already exists in the database
$check_existing_user_query = "SELECT * FROM signup WHERE  email = '$user_email'";
$existing_user_result = mysqli_query($connect, $check_existing_user_query);

if (mysqli_num_rows($existing_user_result) > 0) {
    // Handle case where username or email already exists
    echo " Email already exists!";
    exit();
}

/* Insert user's information into the database
$insert_user_information = "INSERT INTO signup (username, email, password, height, weight, age, gender)
                            VALUES ('$user_name', '$user_email', '$hashed_password', '$user_height', '$user_weight', '$user_age', '$user_gender')";*/

/*
if (mysqli_query($connect, $insert_user_information)) {
    // Get the user ID of the inserted user
    $user_id_query = mysqli_query($connect, "SELECT LAST_INSERT_ID() as user_id");
    $user_id_row = mysqli_fetch_assoc($user_id_query);
    $user_id = $user_id_row['user_id'];

    // Store user information in session variables
    $_SESSION['user_id'] = $user_id;
	 exit();}
	*/
	
      $_SESSION['username'] = $user_name;
	    $_SESSION['password'] = $hashed_password;
	      $_SESSION['email'] = $user_email;
	        $_SESSION['height'] = $user_height;
		        $_SESSION['weight'] = $user_weight;
			       $_SESSION['age'] = $user_age;
				      $_SESSION['gender'] = $user_gender;
					     


// Function to generate a unique 6-digit ID
function generateUniqueID() {
  return mt_rand(100000, 999999); // Generates a random 6-digit number
}



// Function to check if the generated ID exists in our database
function isIDExists($user_id, $connect) {
    $sql = "SELECT * FROM signup WHERE user_id = ?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['count'] > 0; // Returns true if ID exists, false otherwise
}

// Generate a unique ID and check if it exists in the database
do {
    $user_id = generateUniqueID();
} while (isIDExists($user_id, $connect));

// Save the generated unique ID in a session variables
 $_SESSION['user_id']=$user_id;
					
	
    // Redirect to the active page
    header('Location: active.html');
   
// Close database connection
mysqli_close($connect);
?>
</body>
</html>