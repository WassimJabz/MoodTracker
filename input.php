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
     $email = $_SESSION["email"];
     $date = $_POST["date"];
     $sleep = $_POST["sleep"];
     $social = $_POST["social"];
     $exercise = $_POST["exercise"];
     $goals = $_POST["goals"];
     $stress = $_POST["stress"];
     $mood = $_POST["mood"];

     //Inserting the data into our database
     $insertquery = "INSERT INTO logtable (email, fulldate, sleep, interaction, exercise, satisfaction, stress, mood)
     VALUES ('$email', '$date', '$sleep', '$social', '$exercise', '$goals', '$stress', '$mood')";
     $insertresult = mysqli_query($conn, $insertquery);

     if ($insertresult){
        echo
        "<script> 
        alert('Form submitted successfully. Redirecting to the home page');
        window.location.replace('http://moodtracker.byethost3.com/home.html');
        </script>";
     }
     else{
        echo
        "<script> 
        alert('Failed to submit form. Please try again.');
        window.location.replace('http://moodtracker.byethost3.com/input_form.html');
        </script>";
     }
?>