<?php

    //Destroy the session
    session_start();
    session_destroy();
        
    //Redirecting
    header("Location: http://moodtracker.byethost3.com/signin.html");
    exit();

?>