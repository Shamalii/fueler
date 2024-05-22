<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<?php 
$connect = mysqli_connect("localhost", "root", "12345678");
$protein_selection;
$calorie_selection;
mysqli_select_db($connect, "fitnut");
$h1_content = "Results Found";

if (isset($_POST['submit'])) {

    if (!isset($_POST['calory']) && !isset($_POST['protein'])) {
        $sql = "SELECT * FROM meals";
        $result = $connect->query($sql);
        while ($row = mysqli_fetch_assoc($result)) {
        $meals[] = array(
            "meal_name" => $row["meal_name"],
            "calories" => $row["calories"],
            "protein" => $row["protein"],
            "carbs" => $row["carbs"],
            "fat" => $row["fat"],
            "meal_photo" => $row["meal_photo"]
        );
   
        $json_data = json_encode($meals);
    // Write JSON data to data.json file
    $file = fopen("library.json", "w");
    fwrite($file, $json_data);fclose($file); }
    header("Location: library.html");
    exit; 

}
}
if (isset($_POST['calory'])) {
    $calorie_selection = $_POST['calory'];
}

if (isset($_POST['protein'])) {
    $protein_selection = $_POST['protein'];
  }
  
  $sql = "SELECT * FROM meals WHERE ";
  $has_condition = false;  // Flag to track if a condition has been added
  
  if ($calorie_selection == "less than 300") {
    $sql .= "calories < 300 ";
    $has_condition = true;
  } else if ($calorie_selection == "less than 400") {
    $sql .= "calories BETWEEN 300 AND 400 ";
    $has_condition = true;
  } elseif ($calorie_selection == "more than 500") {
    $sql .= "calories > 500 ";
    $has_condition = true;
  }
  
  if (isset($protein_selection)) {
    if ($protein_selection == "less than 20") {
      $sql .= ($has_condition ? "AND " : "") . "protein < 20";
      $has_condition = true;
    } else if ($protein_selection == "less than 40") {
      $sql .= ($has_condition ? "AND " : "") . "protein < 40";
      $has_condition = true;
    } elseif ($protein_selection == "more than 40") {
      $sql .= ($has_condition ? "AND " : "") . "protein > 40";
      $has_condition = true;
    }
  }
  
  // Only add the WHERE clause if there's at least one condition
  if ($has_condition) {
    $sql .= " ;";  // Add semicolon at the end of the query
  } else {
    $sql = "SELECT * FROM meals";  // Default query to select all meals if no conditions
  }
  
  $result = mysqli_query($connect, $sql);$row = mysqli_fetch_assoc($result);
  if(!$row["meal_name"]){
    $file = fopen('library.json', 'w');
        fclose($file);
  }
if (isset($calorie_selection) || isset($protein_selection)) {
    // Removing the last 'AND'
    $sql = rtrim($sql, 'AND ') . ' ';

    $result = mysqli_query($connect, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Process the results (e.g., add meals to the selected_meals array)
        
        while ($row = mysqli_fetch_assoc($result)) {
            $meals[] = array(
                "meal_name" => $row["meal_name"],
                "calories" => $row["calories"],
                "protein" => $row["protein"],
                "carbs" => $row["carbs"],
                "fat" => $row["fat"],
                "meal_photo" => $row["meal_photo"]
            );
            $json_data = json_encode($meals);
        // Write JSON data to data.json file
        $file = fopen("library.json", "w");
        fwrite($file, $json_data);fclose($file);
        }
    } else {
        echo "No meals found in that range.";
}



header("Location: library.html");

exit; 

}


mysqli_close($connect);


?>
</body>
</html>