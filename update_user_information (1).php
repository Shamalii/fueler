<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

  // Sanitize and validate user inputs
    $updated_username = mysqli_real_escape_string($connect, $_POST['username']);
    $updated_email = mysqli_real_escape_string($connect, $_POST['email']);
    $updated_password = mysqli_real_escape_string($connect, $_POST['password']);
    $updated_weight = mysqli_real_escape_string($connect, $_POST['weight']);
    $updated_height = mysqli_real_escape_string($connect, $_POST['height']);
    $updated_age = mysqli_real_escape_string($connect, $_POST['age']);
    $updated_gender = mysqli_real_escape_string($connect, $_POST['gender']);
    $updated_activity = mysqli_real_escape_string($connect, $_POST['Activity_Level']);
    $updated_goal = mysqli_real_escape_string($connect, $_POST['goal']);

$hashed_password = password_hash($updated_password, PASSWORD_DEFAULT);


$updated_nut = isset($_POST['Nut']) ? $_POST['Nut'] : '';
$updated_lactose = isset($_POST['Lactose']) ? $_POST['Lactose'] : '';
$updated_wheat = isset($_POST['Wheat']) ? $_POST['Wheat'] : '';

// Convert checkbox values to 1 or 0
$updated_nut = ($updated_nut == "on") ? "1" : "0";
$updated_lactose = ($updated_lactose == "on") ? "1" : "0";
$updated_wheat = ($updated_wheat == "on") ? "1" : "0";



// Update user's information in the session
    $_SESSION['username'] = $updated_username;
    $_SESSION['email'] = $updated_email;
    $_SESSION['age'] = $updated_age;
    $_SESSION['gender'] = $updated_gender;
    $_SESSION['height'] = $updated_height;
    $_SESSION['weight'] = $updated_weight;
    $_SESSION['activity_level']=$updated_activity;
    $_SESSION['goal']=$updated_goal;
    $_SESSION['nut'] =$updated_nut;
    $_SESSION['lactose']=$updated_lactose;
    $_SESSION['wheat'] =$updated_wheat;
	
// Update user's information in the session and database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  // Check if the updated email already exists
$checkEmailSql = "SELECT COUNT(*) AS email_count FROM signup WHERE email = '$updated_email' AND user_id != " . $_SESSION['user_id'];
$checkEmailResult = mysqli_query($connect, $checkEmailSql);

if ($checkEmailResult) {
    $emailCountRow = mysqli_fetch_assoc($checkEmailResult);
    $emailCount = $emailCountRow['email_count'];

    if ($emailCount > 0) {
        // Email already exists, show message to the user
        echo "Email is already in use. Please choose a different email.";
    } else {
		
    // Update user's information in the database
$updateSql1 = "UPDATE signup SET 
            username = '$updated_username', 
			password1 = '$hashed_password',
            email = '$updated_email', 
            age = '$updated_age', 
            gender = '$updated_gender', 
            height = '$updated_height', 
            weight1 = '$updated_weight' 
            WHERE user_id = " . $_SESSION['user_id'];
			
	}
	
	if (!mysqli_query($connect, $updateSql1)) {
           echo "Error updating record: signup " . mysqli_error($connect);
	}
	
}
	
}

$updateSql2 = "UPDATE user_activity_goal SET 
    activity_level = '$updated_activity', 
    goal = '$updated_goal'
    WHERE user_id = " . $_SESSION['user_id'];
mysqli_query($connect, $updateSql2);


  if (!mysqli_query($connect, $updateSql2)) {
        echo "Error updating record: sql2 ";
        exit(); // Terminate script
    }
	
$updateSql3 = "UPDATE allergies SET 
    nut = '$updated_nut', 
    lactose = '$updated_lactose', 
    wheat = '$updated_wheat'
    WHERE user_id = " . $_SESSION['user_id'];
mysqli_query($connect, $updateSql3);
		
		
		
		  if (!mysqli_query($connect, $updateSql3)) {
        echo "Error updating record: sql3" ;
        exit(); // Terminate script
    }


// Calculate macronutrients and store them in the user_calories table
calculateAndStoreMacronutrients($connect, $_SESSION['user_id'], $updated_age , $updated_gender , $updated_height , $updated_weight , $updated_activity , $updated_goal );


// Function to calculate and store macronutrients
function calculateAndStoreMacronutrients($connect, $user_id, $age, $gender, $height, $weight, $activity_level, $goal) {
    // Calculate Basal Metabolic Rate (BMR) based on gender
    if ($gender == 'male') {
        $bmr = 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);
    } elseif ($gender == 'female') {
        $bmr = 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
    } else {
        die("Invalid gender specified.");
    }

    // Apply activity factor
    $activity_factors = array(
        'Very Low Active' => 1.2,
        'Low Active' => 1.375,
        'Moderate Active' => 1.55,
        'High Active' => 1.725,
        'Very High Active' => 1.9
    );

    // Check if activity level is set and valid
    if (isset($activity_factors[$activity_level])) {
        $tdee = $bmr * $activity_factors[$activity_level];

        // Adjust for fitness goals
        if ($goal == 'Lose') {
            $calorie_needs = $tdee - 500; // Create a calorie deficit
        } elseif ($goal == 'Maintain') {
            $calorie_needs = $tdee; // Keep calorie intake equal to TDEE
        } elseif ($goal == 'Build') {
            $calorie_needs = $tdee + 250; // Create a calorie surplus
        } else {
            die("Invalid fitness goal specified.");
        }

    }
            //Calculate macronutrients
            $fat_percentage = 25;
            $carb_percentage = 50;
            $protein_percentage = 25;

            $fat = ($fat_percentage / 100) * $calorie_needs / 9;
            $carbs = ($carb_percentage / 100) * $calorie_needs / 4;
            $protein = ($protein_percentage / 100) * $calorie_needs / 4;

            // Store calculated values in session variables
            $_SESSION['calories'] = $calorie_needs;
            $_SESSION['fat'] = $fat;
            $_SESSION['carbs'] = $carbs;
            $_SESSION['protein'] = $protein;
			
			
// update calculated values of user_calories table
$updateSql4 = "UPDATE user_calories SET 
fat = '$fat ' ,
protein = '$protein ' ,
carbs = '$carbs ' ,
calories = '$calorie_needs'
    WHERE user_id = " . $_SESSION['user_id'];

mysqli_query($connect, $updateSql4);

  if (!mysqli_query($connect, $updateSql4)) {
        echo "Error updating record: ssql4 " ;
        exit(); // Terminate script
    }
}

//info.json
$user_id=$_SESSION['user_id'];
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



 $sql20 = "SELECT activity_level, goal FROM user_activity_goal WHERE user_id = ?";
 $stmt = $connect->prepare($sql20);
 $stmt->bind_param("i", $user_id);
 $stmt->execute();
 $result20 = $stmt->get_result();
 
 
 
 
 if ($result20->num_rows > 0) {
     $row = $result20->fetch_assoc();
 
     // Create an array to hold the retrieved data
     $data = array(
         "activity_level" => $row["activity_level"],
         "goal" => $row["goal"],
     );
 
     // Encode the array to JSON format
     $json_data = json_encode($data);
 
     // Open the JSON file for writing in write mode ("w")
     $file = fopen("active.json", "w");
 
     // Check if file opening was successful
     if ($file !== false) {
         // Write the JSON data to the file
         fwrite($file, $json_data);
         echo "Data inserted into active.json successfully!";
     } else {
         echo "Error opening active.json for writing.";
     }
 
     fclose($file);}




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


 //check if the user email and password are match in database 
 $sql30 = "SELECT username, email, password1, height, weight1, age, gender, user_id FROM signup WHERE user_id = '" . $_SESSION['user_id'] . "'";
 $result30 = mysqli_query($connect, $sql30);

 if ($row30 = mysqli_fetch_assoc($result30)) {
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
   $file = fopen("allinfo.json", "w") or die("Unable to open file!");
 
   // Write the JSON data to the file
   fwrite($file, $json_data);
 
   // Close the file
   fclose($file);

 }

// Redirect to profile.html after successful processing
    header("Location: main.html");
	exit(); // Terminate script
	
// Close database connection
          mysqli_close($connect);
?>
