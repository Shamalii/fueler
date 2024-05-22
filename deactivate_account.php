<?php

// Start the session
session_start();

// Connect with server 
$connect = mysqli_connect("localhost", "root", "12345678", "fitnut");

// Check the connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get form data and validate
$email = mysqli_real_escape_string($connect, $_POST['email']);
$username = mysqli_real_escape_string($connect, $_POST['username']);
$password = mysqli_real_escape_string($connect, $_POST['password']);

// Validate user credentials
$sql = "SELECT * FROM signup WHERE email='$email' AND username='$username'";
$result = mysqli_query($connect, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $hashed_password = $row['password1'];

    // Verify the entered password against the hashed password
    if (password_verify($password, $hashed_password)) {
        // Get the user_id
        $user_id = $row['user_id'];

        // Delete user data from tables
        $delete_signup_sql = "DELETE FROM signup WHERE user_id='$user_id'";
        mysqli_query($connect, $delete_signup_sql);

        $delete_calories_sql = "DELETE FROM user_calories WHERE user_id='$user_id'";
        mysqli_query($connect, $delete_calories_sql);

        $delete_activity_goal_sql = "DELETE FROM user_activity_goal WHERE user_id='$user_id'";
        mysqli_query($connect, $delete_activity_goal_sql);

        $delete_allergies_sql = "DELETE FROM allergies WHERE user_id='$user_id'";
        mysqli_query($connect, $delete_allergies_sql);

        // End the session and unset session variables
        session_unset();
        session_destroy();
        mysqli_close($connect);
        header('Location: landing.html');
        exit();
    } else {
        echo "Invalid password. Please try again.";
    }
} else {
    echo "Invalid email or username. Please try again.";
}

// Close database connection
mysqli_close($connect);
?>