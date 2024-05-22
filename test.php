<html>
<head><meta charset="UTF-8">
</head>
<body>
<?php 
    $connect = mysqli_connect("localhost", "root", "12345678", "fitnut");



    $user_id = 28;
    $sql = "SELECT gender , email FROM signup";
    $result = mysqli_query($connect, $sql);

    // Fetch data from the result object
    $emails = array();
    while($row = mysqli_fetch_assoc($result)){
        $emails[] =array(
            'gender' => $row['gender'],
            'email' => $row['email']

        ); 
    }
    
    

    // Encode fetched data into JSON
    $json_data = json_encode($emails);

   

    // Write JSON data to file
    file_put_contents("test.json", $json_data);

    // Close database connection
    mysqli_close($connect);
?>
</body>
</html>
