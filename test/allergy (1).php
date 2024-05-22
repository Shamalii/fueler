<?php 
session_start();

$connect=mysqli_connect("localhost", "root", "12345678");
mysqli_select_db($connect, "fitnut");

// Check database connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve user input from POST and session variables
$nut = isset($_POST['Nut']) ? $_POST['Nut'] : '';
$lactose = isset($_POST['lactose']) ? $_POST['lactose'] : '';
$wheat = isset($_POST['Wheat']) ? $_POST['Wheat'] : '';

// Convert checkbox values to 1 or 0
$nut = ($nut == "on") ? "1" : "0";
$lactose = ($lactose == "on") ? "1" : "0";
$wheat = ($wheat == "on") ? "1" : "0";

// Store checkbox values in session variables
$_SESSION['nut'] = $nut;
$_SESSION['lactose'] = $lactose;
$_SESSION['wheat'] = $wheat;

// Retrieve other session variables
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

/*
foreach ($tables_to_check as $table) {
    $check_user_id_query = "SELECT user_id FROM $table WHERE user_id = '$user_id'";
    $check_user_id_result = mysqli_query($connect, $check_user_id_query);
    if(mysqli_num_rows($check_user_id_result) > 0) {
        die("User with the same user_id already exists in the $table table.");
    }
}
*/

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



// Insert allergy information into the database
$insert_user_information1 = "INSERT INTO allergies (user_id, lactose, nut, wheat) 
                             VALUES ('$user_id', '$lactose', '$nut', '$wheat')";
if (!mysqli_query($connect, $insert_user_information1)) {
    die("Error inserting allergy information: " . mysqli_error($connect));
}

// Insert activity level information into the database
$insert_user_information2 = "INSERT INTO user_activity_goal (user_id, activity_level, goal) 
                             VALUES ('$user_id', '$activity_level', '$goal')";
if (!mysqli_query($connect, $insert_user_information2)) {
    die("Error inserting activity level information: " . mysqli_error($connect));
}

// Insert user's information into the database 
$insert_user_information3 = "INSERT INTO signup (username, email, password, height, weight, age, gender, user_id) 
                             VALUES ('$username', '$email','$password', '$height', '$weight', '$age', '$gender', '$user_id')";
if (!mysqli_query($connect, $insert_user_information3)) {
    die("Error inserting user information: " . mysqli_error($connect));
} 




// Calculate macronutrients and store them in the user_calories table
calculateAndStoreMacronutrients($connect, $user_id, $age, $gender, $height, $weight, $activity_level, $goal);

// Function to calculate and store macronutrients
function calculateAndStoreMacronutrients($connect, $user_id, $age, $gender, $height, $weight, $activity_level, $goal) {
    // Calculation of macronutrients
    // Ensure proper validation of input before calculations

    // Error handling for invalid gender
    if (!in_array($gender, ['male', 'female'])) {
        die("Invalid gender specified.");
    }

    // Calculate Basal Metabolic Rate (BMR) based on gender
    if ($gender == 'male') {
        $bmr = 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);
    } elseif ($gender == 'female') {
        $bmr = 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
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
    if (!isset($activity_factors[$activity_level])) {
        die("Invalid activity level specified.");
    }

    // Calculate Total Daily Energy Expenditure (TDEE)
    $tdee = $bmr * $activity_factors[$activity_level];

    // Adjust for fitness goals
    if ($goal == 'Lose') {
        $calorie_needs = $tdee - 500; // Create a calorie deficit
    } elseif ($goal == 'Maintain') {
        $calorie_needs = $tdee; // Keep calorie intake equal to TDEE
    } elseif ($goal == 'Build') {
        $calorie_needs = $tdee + 250; // Create a calorie surplus
    }

    // Calculate macronutrients
    $fat_percentage = 25;
    $carb_percentage = 50;
    $protein_percentage = 25;

    $fat = ($fat_percentage / 100) * $calorie_needs / 9;
    $carbs = ($carb_percentage / 100) * $calorie_needs / 4;
    $protein = ($protein_percentage / 100) * $calorie_needs / 4;

    // Insert calculated values into user_calories table
    $insert_user_calories = "INSERT INTO user_calories (fat, protein, carbs, calories, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $insert_user_calories);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ddddd", $fat, $protein, $carbs, $calorie_needs, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        die("Error preparing statement: " . mysqli_error($connect));
    }
}



// Close database connection
mysqli_close($connect);

// Redirect to active.html after successful processing
header("Location: update.html");
exit();
?>
