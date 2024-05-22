<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<?php 

session_start();
$connect=mysqli_connect( "localhost" ,"root", "12345678" );

mysqli_select_db($connect,"fitnut");


//adding image to table
/*for ($i = 1; $i <= 58; $i++) {
    $img_name = "img" . $i;
    $insert_user_informati = "UPDATE lactos_allergy SET meal_photo = '$img_name' WHERE nim = $i";
     mysqli_query($connect, $insert_user_informati);
}*/

// Retrieve user input from POST and session variables
$nut = isset($_POST['Nut']) ? $_POST['Nut'] : '';
$lactose = isset($_POST['lactose']) ? $_POST['lactose'] : '';
$wheat = isset($_POST['Wheat']) ? $_POST['Wheat'] : '';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_id = $_SESSION['user_id'];

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$height = $_SESSION['height'];
$weight = $_SESSION['weight'];
$age = $_SESSION['age'];
$gender = $_SESSION['gender'];
$activity_level = $_SESSION['activity_level'];
$goal = $_SESSION['goal'];
$password = $_SESSION['password'];


// Check if user_id already exists in each of the tables
$tables_to_check = array("signup", "allergies", "user_activity_goal");


foreach ($tables_to_check as $table) {
    $check_user_id_query = "SELECT user_id FROM $table WHERE user_id = '$user_id'";
    $check_user_id_result = mysqli_query($connect, $check_user_id_query);
    if(mysqli_num_rows($check_user_id_result) > 0) {
        echo '<div style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 10px; padding: 30px; width: 400px; text-align: center;">';
        echo '<p style="font-size: 25px;">User with the same user_id already exists in the ' . $table . ' table. Please ';
        echo '<a href="login.html" style="color: #721c24; text-decoration: underline;">login</a>';
        echo ' or ';
        echo '<a href="signup.html" style="color: #721c24; text-decoration: underline;">sign up</a>';
        echo '.</p>';
        echo '</div>';
        exit(); // Terminate script after displaying the message
    }
}


// Insert activity level information into the database
$insert_user_information2 = "INSERT INTO user_activity_goal (user_id, activity_level, goal) 
                             VALUES ('$user_id', '$activity_level', '$goal')";
if (!mysqli_query($connect, $insert_user_information2)) {
    die("Error inserting activity level information: " . mysqli_error($connect));
}

// Insert user's information into the database 
$insert_user_information3 = "INSERT INTO signup (username, email, password1 , height, weight1 , age, gender, user_id) 
                             VALUES ('$username', '$email','$password', '$height', '$weight', '$age', '$gender', '$user_id')";
if (!mysqli_query($connect, $insert_user_information3)) {
    die("Error inserting user information: " . mysqli_error($connect));
}



// SQL query with parameterized statement to avoid SQL injection
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

    fclose($file);
} else {
    echo "No data found for user ID: $id20";
}

echo "<br>User ID: $id20<br>";
if ($result20->num_rows > 0) {
    echo "Data retrieved successfully<br>";
} else {
    echo "No data found for user ID: $id20<br>";
}

$stmt->close();
// Convert checkbox values to 1 or 0
$nut = ($nut == "on") ? "1" : "0";   $lactose = ($lactose == "on") ? "1" : "0";   $wheat = ($wheat == "on") ? "1" : "0"; 


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

//import to json
$data = [
    "nut" => $nut,
    "lactose" => $lactose,
    "wheat" => $wheat,
  ];
  
  // Encode the data to JSON format
  $jsonData = json_encode($data);

  // Open the allergy.json file for writing (with error handling)
  $file = fopen("allergies.json", "w") or die("Unable to open file!");
  
  // Write the JSON data to the file
  fwrite($file, $jsonData);
  
  // Close the file
  fclose($file);
  
  // end of import to json




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

/*
if($nut == 0 && $lactose == 0 && $wheat == 0){
    $table1_data = array();
    $sql1 = "SELECT meal_name , calories , protein  FROM nuts_allergy";
    $result1 = mysqli_query($connect, $sql1);
    while ($row = mysqli_fetch_assoc($result1)) {
      $table1_data[] = $row;
    }
    
    $table2_data = array();
    $sql2 = "SELECT meal_name , calories , protein  FROM lactos_allergy";
    $result2 = mysqli_query($connect, $sql2);
    while ($row = mysqli_fetch_assoc($result2)) {
      $table2_data[] = $row;
    }

    $table3_data = array();
    $sql3 = "SELECT meal_name , calories , protein  FROM wheat_allergy";
    $result3 = mysqli_query($connect, $sql3);
    while ($row = mysqli_fetch_assoc($result3)) {
      $table3_data[] = $row;
    }

    $data = array_merge($table1_data, $table2_data);

    $json_data = json_encode($data);

    $filename = "data.json";
    $handle = fopen($filename, 'w');
    fwrite($handle, $json_data);
    fclose($handle);
}*/

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
  //meals(default) to data.json
  
  
// Insert allergy information into the database
$insert_user_information = "INSERT INTO allergies(user_id, lactose, nut, wheat) VALUES ('$user_id', '$lactose', '$nut', '$wheat')";
if (!mysqli_query($connect, $insert_user_information)) {
    // Log the error to a file
    error_log("Error: " . mysqli_error($connect), 3, "error.log");
    // Provide a user-friendly error message
    die("An error occurred. Please try again later.");
}
$sql10 = "SELECT username, email, password1, height, weight1, age, gender, user_id FROM signup WHERE user_id = '" . $_SESSION['user_id'] . "'";$result10 = mysqli_query($connect, $sql10);

if ($row10 = mysqli_fetch_assoc($result10)) {
  // Create the data array (exclude password for security)
  $data = [
    "username" => $row10['username'],
    "email" => $row10['email'],
    // "password" => $row10['password'],  <-- Exclude password for security
    "height" => $row10['height'],
    "weight" => $row10['weight1'],
    "age" => $row10['age'],
    "gender" => $row10['gender'],
    "user_id" => $row10['user_id'],
  ];

  // Encode the data array to JSON
  $json_data = json_encode($data);

  // Open the file for writing
  $file = fopen("allinfo.json", "w") or die("Unable to open file!");

  // Write the JSON data to the file
  fwrite($file, $json_data);

  // Close the file
  fclose($file);

  echo "User information written to info.json successfully!";
} else {
  echo "No user information found for user ID: " . $_SESSION['user_id'];
}
// Retrieve user information from the signup table using session user ID
if (!empty($user_id)) {
    // Prepare and execute query to retrieve user information
    $sql = "SELECT age, gender, height, weight1 FROM signup WHERE user_id = ?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if user information is found
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $age = $row['age'];
        $gender = $row['gender'];
        $height = $row['height'];
        $weight = $row['weight1'];

        // Retrieve activity level and goal from the user_activity_goal table
        $sql = "SELECT activity_level, goal FROM user_activity_goal WHERE user_id = ?";
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Check if activity level and goal are found
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['activity_level'] = $row['activity_level'];
            $_SESSION['goal'] = $row['goal'];

            // Calculate macronutrients and store them in the user_calories table
            calculateAndStoreMacronutrients($connect, $user_id, $age, $gender, $height, $weight);

            // Redirect to Main.php after successful processing
            header('Location: Main.html');
            exit();
        } else {
            die("No activity level and goal found for the user.");
        }
    } else {
        die("No user information found for the specified user ID.");
    }
}
//active.json






// Close database connection
mysqli_close($connect);

// Function to calculate and store macronutrients
function calculateAndStoreMacronutrients($connect, $user_id, $age, $gender, $height, $weight)
{
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
    if (isset($_SESSION['activity_level']) && array_key_exists($_SESSION['activity_level'], $activity_factors)) {
        $tdee = $bmr * $activity_factors[$_SESSION['activity_level']];

        // Adjust for fitness goals
        if (isset($_SESSION['goal'])) {
            if ($_SESSION['goal'] == 'Lose') {
                $calorie_needs = $tdee - 500; // Create a calorie deficit
            } elseif ($_SESSION['goal'] == 'Maintain') {
                $calorie_needs = $tdee; // Keep calorie intake equal to TDEE
            } elseif ($_SESSION['goal'] == 'Build') {
                $calorie_needs = $tdee + 250; // Create a calorie surplus
            } else {
                die("Invalid fitness goal specified.");
            }

            // Calculate macronutrients
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
            $id=$_SESSION['user_id'];
            //store info to info_json
            $data = array(
                "calories" => $_SESSION['calories'],
                "fat" => $_SESSION['fat'],
                "carbs" => $_SESSION['carbs'],
                "protein" => $_SESSION['protein'],
                "user_id" => $_SESSION['user_id'],
              );
              $json_data = json_encode($data);
              $filename = 'info.json';
              $handle = fopen($filename, 'w');
              fwrite($handle, $json_data);
              fclose($handle);
              //insert info to database
            $insert_user_informationn = "INSERT INTO user_calories(`fat`, `protein`, `carbs`, `calories`, `user_id`) VALUES ('$fat','$protein','$carbs','$calorie_needs','$id')";
            if (!mysqli_query($connect, $insert_user_informationn)) {
                // Log the error to a file
                error_log("Error: " . mysqli_error($connect), 3, "error.log");
                // Provide a user-friendly error message
                die("An error occurred. Please try again later.");
            }
            // Insert calculated values into user_calories table
        } else {
            die("Fitness goal not set.");
        }
    } 
}



//JSON PHP
$id=$_SESSION['user_id'];

$sql = "SELECT calories FROM user_calories WHERE user_id = $id";
$result = $connect->query($sql);
if ($result->num_rows > 0) {
    // Fetch the first row as an associative array
    $row = $result->fetch_assoc();
    $calories = $row["calories"];
  } else {
    echo "No matching data found";
    exit();
  }

  $data = array(
    "calories" => $calories
  );
  $json_data = json_encode($data);

  //end of json php
  $filename = "macros.json";
   file_put_contents($filename, $json_data);


   //adding meals to meal-library

   



   //allinfo.json
  
   
?>

</body>
</html>