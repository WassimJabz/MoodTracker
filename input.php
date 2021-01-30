<?php
    //Credentials
    $servername = "sql104.byethost3.com";
    $username = "b3_27816027";
    $password = "Pass12345@";
    $dbname = "b3_27816027_Login";

     // Creating connection
     $conn = mysqli_connect($servername, $username, $password, $dbname);

     // Checking connection
     if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
     }

     //Extracting the user data
     $date = $_POST["date"];
     $sleep = $_POST["sleep"];
     $social = $_POST["social"];
     $exercise = $_POST["exercise"];
     $goals = $_POST["goals"];
     $stress = $_POST["stress"];
     $mood = $_POST["mood"];
?>