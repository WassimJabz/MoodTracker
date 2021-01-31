<?php
    session_start();
    if(!isset($_SESSION['loggedin'])){
        header("Location: http://moodtracker.byethost3.com/signin.html");
        exit();
    }
    else{
        header("Location: http://moodtracker.byethost3.com/home.html");
        exit();
    }
?>