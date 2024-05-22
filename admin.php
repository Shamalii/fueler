
<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
$connect = mysqli_connect("localhost", "root", "12345678", "fitnut");

// Check the connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $meal_name = $_POST['meal_name'];
    $ingredients = $_POST['ingredients'];
    $fat = $_POST['fat'];
    $protein = $_POST['protein'];
    $carbs = $_POST['carbs'];
    $calories = $_POST['calories'];

    // Check if a file was uploaded
    if ($_FILES['meal_photo']['error'] === UPLOAD_ERR_OK) {
        // Get the name and extension of the uploaded file
        $file_name = basename($_FILES['meal_photo']['name']);
        $file_tmp = $_FILES['meal_photo']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Iterate through each selected table
        if (!empty($_POST['tables'])) {
            $selected_tables = $_POST['tables'];
            foreach ($selected_tables as $selected_table) {
                // Count the number of rows in the current table
                $result = mysqli_query($connect, "SELECT COUNT(*) as row_count FROM $selected_table");
                $row = mysqli_fetch_assoc($result);
                $row_count = $row['row_count'];

                // Define the new file name using the row count
                $new_file_name = "img" . ($row_count + 1);

                // Define the upload directory based on the current table
                switch ($selected_table) {
                    case 'meals':
                        $upload_dir = 'images/';
                        break;
                    case 'nuts_allergy':
                        $upload_dir = 'nut_allergy_photos/';
                        break;
                    case 'wheat_allergy':
                        $upload_dir = 'wheat_allergy_photos/';
                        break;
                    case 'lactos_allergy':
                        $upload_dir = 'lactose_allergy/';
                        break;
                    default:
                        $upload_dir = 'uploads/'; // Default directory
                }

                // Ensure the directory exists, if not create it
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                // Move the uploaded file to the desired location with the new file name
                $file_path = $upload_dir . $new_file_name . '.' . $file_ext;
                if (move_uploaded_file($file_tmp, $file_path)) {
                    echo "File uploaded successfully to $selected_table folder.<br>";
                } else {
                    echo "Error uploading file to $selected_table folder. Please try again later.<br>";
                }

                // Insert into the selected table
                $sql = "INSERT INTO $selected_table (meal_name, ingredients, fat, protein, carbs, calories, meal_photo) VALUES (?, ?, ?, ?, ?, ?, ?)";

                // Prepare statement
                $stmt = mysqli_prepare($connect, $sql);

                if ($stmt === false) {
                    die("Error: Unable to prepare statement. SQL error: " . mysqli_error($connect));
                }

                // Bind parameters
                $bind_result = mysqli_stmt_bind_param($stmt, "ssdddds", $meal_name, $ingredients, $fat, $protein, $carbs, $calories, $new_file_name);

                if ($bind_result === false) {
                    die("Error: Unable to bind parameters. SQL error: " . mysqli_error($connect));
                }

                // Execute statement
                $execute_result = mysqli_stmt_execute($stmt);

                if ($execute_result === false) {
                    die("Error: Unable to execute statement. SQL error: " . mysqli_error($connect));
                }

                echo "Meal inserted successfully into $selected_table table.<br>";
            }
        } else {
            echo "Please select at least one table.";
        }
    } else {
        // Handle file upload errors
        switch ($_FILES['meal_photo']['error']) {
            case UPLOAD_ERR_NO_FILE:
                echo "No file was uploaded.";
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                echo "The uploaded file exceeds the maximum file size limit.";
                break;
            case UPLOAD_ERR_PARTIAL:
                echo "The uploaded file was only partially uploaded.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                echo "Missing temporary folder.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                echo "Failed to write file to disk.";
                break;
            case UPLOAD_ERR_EXTENSION:
                echo "A PHP extension stopped the file upload.";
                break;
            default:
                echo "Unknown error occurred.";
                break;
        }
        exit; // Exit script if no file uploaded or error occurred
    }
}

// Close the connection
mysqli_close($connect);

?>



