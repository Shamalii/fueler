<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<?php
session_start();
//connect with server  done 
$connect=mysqli_connect( "localhost" ,"root", "12345678");

//select database (login)
$loginform=mysqli_select_db($connect,"fitnut");

// get user's information from html page (login_form) using (form)
 $user_email=$_POST["email_2"];
 $user_password=$_POST["password_2"];
 //Encrypt the password
//$hashed_login = password_hash($user_password, PASSWORD_DEFAULT);


//get user's information from database
$id_query = "SELECT user_id, password1 FROM signup WHERE email = '$user_email'";
$result0 = mysqli_query($connect, $id_query);
/*$row = mysqli_fetch_assoc($result0);
$user_id = $row['user_id'];
if ($row = mysqli_fetch_assoc($result0)) {
    $user_id = $row['user_id'];  // Access user_id using its key in the associative array
  } else {
    // Handle the case where no user was found (e.g., display error message)
    echo "Invalid email or password";
  }*/
  $user_id;
  $id_query0 = "SELECT user_id FROM signup WHERE email = ?";
$stmt = mysqli_prepare($connect, $id_query0);
mysqli_stmt_bind_param($stmt, "s", $user_email);
mysqli_stmt_execute($stmt);
$result00 = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result00)) {
    $user_id = $row['user_id'];
} else {
    echo "No user found with the provided email.";
}
// Close the statement
mysqli_stmt_close($stmt);
  if ($result0 && mysqli_num_rows($result0) > 0) {
    $row = mysqli_fetch_assoc($result0);
    $hashed_password = $row['password1'];

    // Verify the provided password against the hashed password stored in the database
    if (password_verify($user_password, $hashed_password)) {
        // Passwords match, redirect to main page or do whatever is necessary
       
    } else {
        // Passwords do not match, handle incorrect password case
        echo "Invalid email or password";
    }
} else {
    // No user found with the given email address, handle this case
    echo "Invalid email or password";
}
$sql22 = "SELECT ingredients, meal_photo FROM meals";
$result = $connect->query($sql22);

$data = array(); // Use $data to store the fetched rows

if ($result->num_rows > 0) {
    // Fetch all rows as an associative array
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo "0 results";
}






// Convert the data to JSON and save to a file
$json_data = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents('allingrediants.json', $json_data); 
$sql25 = "SELECT activity_level, goal FROM user_activity_goal WHERE user_id = $user_id";
$result25 = $connect->query($sql25);

if ($result25->num_rows > 0) {
    // Initialize variables to store activity and goal
    $activity = '';
    $goal = '';

    // Fetch data from the result set
    while ($row = $result25->fetch_assoc()) {
        // Store activity and goal from each row
        $activity = $row['activity_level'];
        $goal = $row['goal'];

        // You can do something with $activity and $goal here if needed
        // For example, echo or manipulate them
    }

    // Close result set
    $result25->close();

    // Close connection

    // You can use $activity and $goal here or perform further operations

    // Convert activity and goal to an array
    $data = array('activity_level' => $activity, 'goal' => $goal);

    // Convert data to JSON format
    $json_data = json_encode($data);

    // Write JSON data to a file
    $file = fopen('active.json', 'w');
    fwrite($file, $json_data);
    fclose($file);

    echo "Data retrieved and written to active.json successfully.";
} else {
    echo "No data found for user_id: $user_id";
}









 $stt = "SELECT lactose, nut, wheat FROM allergies WHERE user_id='$user_id'";


 $result2 = mysqli_query($connect, $stt);
 
 $allergies = [];
 if ($row2 = mysqli_fetch_assoc($result2)) {
    $nut = $row2['nut'];
    $lactose = $row2['lactose'];
    $wheat = $row2['wheat'];
    // Extract allergy values and add them to the array (only if not null)
    if (!is_null($nut)) {
        $allergies['nut'] = $nut;
    }
    if (!is_null($lactose)) {
        $allergies['lactose'] = $lactose;
    }
    if (!is_null($wheat)) {
        $allergies['wheat'] = $wheat;
    }
} else {
    // Handle the case where no row was found (optional)
    // For example, you could set default values for the allergies array
}
 
 
 // Convert array to JSON format
 $json_data = json_encode($allergies);
 
 // Open the JSON file for writing (replace with your desired file path)
 $fp = fopen("allergies.json", "w");
 
 // Check if file opened successfully
 if (!$fp) {
   die("Error opening JSON file for writing.");
 }
 
 // Write JSON data to the file
 fwrite($fp, $json_data);
 
 // Close the file
 fclose($fp);
// SQL query with parameterized statement to avoid SQL injection


//all info json
$sql40 = "SELECT username, email, password1, height, weight1, age, gender, user_id FROM signup WHERE user_id ='$user_id'";
$result22 = mysqli_query($connect, $sql40);

if ($row30 = mysqli_fetch_assoc($result22)) {
  // Create the data array (exclude password for security)
  $data = [
    "username" => $row30['username'],
    "email" => $row30['email'],
    // "password" => $row10['password'],  <-- Exclude password for security
    "height" => $row30['height'],
    "weight" => $row30['weight1'],
    "age" => $row30['age'],
    "gender" => $row30['gender'],
    "user_id" => $row30['user_id'],
  ];

  // Encode the data array to JSON
  $json_data = json_encode($data);

  // Open the file for writing
  $file = fopen("allinfo.json", "w");

  // Write the JSON data to the file
  fwrite($file, $json_data);

  // Close the file
  fclose($file);

}


 $sql = "SELECT meal_name, calories, protein, carbs, fat , meal_photo FROM meals"; /////////////////////////////////////meals default
$sql2 ="SELECT ingredients , meal_photo FROM meals";

$result = mysqli_query($connect, $sql);
if (mysqli_num_rows($result) > 0) {
// Initialize empty array to store meal data
$meals = [];

// Loop through each row in the result set
while($row = mysqli_fetch_assoc($result)) {
 // Add meal data to the $meals array
 $meals[] = $row;
}

// Encode the $meals array to JSON format
$json_data = json_encode($meals);

// Write JSON data to data.json file
$file = fopen("data.json", "w");
fwrite($file, $json_data);fclose($file);
//adding to library
$file2 = fopen("library.json", "w");
fwrite($file2, $json_data);fclose($file2);



}
$result = mysqli_query($connect, $sql2);

if (mysqli_num_rows($result) > 0) {
    // Initialize empty array to store meal data
    $meals = [];
   
    // Loop through each row in the result set
    while($row = mysqli_fetch_assoc($result)) {
      // Add meal data to the $meals array
      $meals[] = $row;
    }
   
    // Encode the $meals array to JSON format
    $json_data = json_encode($meals);
   
    // Write JSON data to data.json file
    $file = fopen("ingrediants.json", "w");
    fwrite($file, $json_data);
    fclose($file);
   
   }






if($nut == 1){
    $sql = "SELECT meal_name, calories, protein, carbs, fat , meal_photo FROM nuts_allergy"; /////////////////////////////////////nut
    $sql2 ="SELECT ingredients , meal_photo FROM nuts_allergy";

    $result = mysqli_query($connect, $sql);
if (mysqli_num_rows($result) > 0) {
 // Initialize empty array to store meal data
 $meals = [];

 // Loop through each row in the result set
 while($row = mysqli_fetch_assoc($result)) {
   // Add meal data to the $meals array
   $meals[] = $row;
 }

 // Encode the $meals array to JSON format
 $json_data = json_encode($meals);

 // Write JSON data to data.json file
 $file = fopen("data.json", "w");
 fwrite($file, $json_data);fclose($file);

}
$result = mysqli_query($connect, $sql2);

if (mysqli_num_rows($result) > 0) {
    // Initialize empty array to store meal data
    $meals = [];
   
    // Loop through each row in the result set
    while($row = mysqli_fetch_assoc($result)) {
      // Add meal data to the $meals array
      $meals[] = $row;
    }
   
    // Encode the $meals array to JSON format
    $json_data = json_encode($meals);
   
    // Write JSON data to data.json file
    $file = fopen("ingrediants.json", "w");
    fwrite($file, $json_data);
    fclose($file);
   
   }
}


/////////////////////////////////////lactose
                                                                            
if($lactose == 1){
    $sql = "SELECT meal_name, calories, protein, carbs, fat , meal_photo FROM lactos_allergy";
    $sql2 ="SELECT ingredients , meal_photo FROM lactos_allergy";
    $result = mysqli_query($connect, $sql);

if (mysqli_num_rows($result) > 0) {
 // Initialize empty array to store meal data
 $meals = [];

 // Loop through each row in the result set
 while($row = mysqli_fetch_assoc($result)) {
   // Add meal data to the $meals array
   $meals[] = $row;
 }

 // Encode the $meals array to JSON format
 $json_data = json_encode($meals);

 // Write JSON data to data.json file
 $file = fopen("data.json", "w");
 fwrite($file, $json_data);fclose($file);
}
$result = mysqli_query($connect, $sql2);

if (mysqli_num_rows($result) > 0) {
    // Initialize empty array to store meal data
    $meals = [];
   
    // Loop through each row in the result set
    while($row = mysqli_fetch_assoc($result)) {
      // Add meal data to the $meals array
      $meals[] = $row;
    }
   
    // Encode the $meals array to JSON format
    $json_data = json_encode($meals);
   
    // Write JSON data to data.json file
    $file = fopen("ingrediants.json", "w");
    fwrite($file, $json_data);
    fclose($file);
   
   }
}
                                                            /////////////////////////////////////wheat
if($wheat == 1){
    $sql = "SELECT meal_name, calories, protein, carbs, fat , meal_photo FROM wheat_allergy";
    $sql2 ="SELECT ingredients , meal_photo FROM wheat_allergy";
    $result = mysqli_query($connect, $sql);

    //for meal_name etc....
if (mysqli_num_rows($result) > 0) {
 // Initialize empty array to store meal data
 $meals = [];

 // Loop through each row in the result set
 while($row = mysqli_fetch_assoc($result)) {
   // Add meal data to the $meals array
   $meals[] = $row;
 }

 // Encode the $meals array to JSON format
 $json_data = json_encode($meals);

 // Write JSON data to data.json file
 $file = fopen("data.json", "w");
 fwrite($file, $json_data);
 fclose($file);

}
//for ingredients
$result = mysqli_query($connect, $sql2);

if (mysqli_num_rows($result) > 0) {
    // Initialize empty array to store meal data
    $meals = [];
   
    // Loop through each row in the result set
    while($row = mysqli_fetch_assoc($result)) {
      // Add meal data to the $meals array
      $meals[] = $row;
    }
   
    // Encode the $meals array to JSON format
    $json_data = json_encode($meals);
   
    // Write JSON data to data.json file
    $file = fopen("ingrediants.json", "w");
    fwrite($file, $json_data);
    fclose($file);
   
   }
} 

//calories div 

$sql3 = "SELECT `fat`, `protein`, `carbs`, `calories`, `user_id` FROM `user_calories` WHERE user_id = $user_id";

$result3 = mysqli_query($connect, $sql3);

if ($row3 = mysqli_fetch_assoc($result3)) {
  // Create the data array
  $data = [
    "fat" => $row3['fat'],
    "protein" => $row3['protein'],
    "carbs" => $row3['carbs'],
    "calories" => $row3['calories'],
    "user_id" => $row3['user_id'],
  ];





  // Encode the data array to JSON
  $json_data = json_encode($data);

  // Open the file for writing
  $file = fopen("info.json", "w") or die("Unable to open file!");

  // Write the JSON data to the file
  fwrite($file, $json_data);

  // Close the file
  fclose($file);

  echo "User calorie information written to info.json successfully!";
} else {
  echo "No calorie information found for user ID: $user_id";
}


header('Location: Main.html');


 //check if the user email and password are match in database 


 mysqli_close($connect);
 
?>
</body >
</html >


