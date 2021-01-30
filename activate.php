<?php

    if(isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['token']) && !empty($_GET['token'])){
        
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

        //If the connection to the database succeeds, extract the credentials
        $useremail = $_GET["email"];
        $usertoken = $_GET["token"];

        //Check if they match the database
        $matchquery = "SELECT * FROM logtable WHERE email = '$useremail'";
        $matchresult = mysqli_query($conn, $matchquery);

        //If the query to extract data is successful
        if($matchresult){

            //If the email exists in the database
            if(mysqli_num_rows($matchresult) > 0){
                
                //Fetching the data as an array to extract the database token
                $userarray = mysqli_fetch_assoc($matchresult);

                //If the tokens match, the user is registered in the database
                if($userarray["token"] == $usertoken){

                    //If the user is not already verified
                    if($userarray['verified'] == 0){
                        
                        //Updating the verified status of the user
                        $verifiedquery = "UPDATE logtable SET verified = 1 WHERE email = '$useremail';";
                        $verifiedresult = mysqli_query($conn, $verifiedquery);
                        //In case the verification is successful
                        if($verifiedresult){
                            echo
                            "<script> 
                            alert('Your account has been verified successfully. Press ok to start using Mood Tracker!');
                            window.location.replace('http://moodtracker.byethost3.com/signin.html');
                            </script>";
                        }
                        //In case the database could not be accessed
                        else{
                            echo
                            "<script> 
                            alert('An error occured while accessing the database. Press ok to Sign Up again.');
                            window.location.replace('http://moodtracker.byethost3.com/signup.html');
                            </script>";
                        }
                    }
                    else{
                        echo
                        "<script> 
                        alert('It looks like your account already is verified. Press ok to Sign In !');
                        window.location.replace('http://moodtracker.byethost3.com/signin.html');
                        </script>";
                    }

                }
                //If the tokens do not match
                else{
                    echo
                    "<script> 
                    alert('The url you just used is not complete. Please check your email address and use the one found there.');
                    window.location.replace('http://moodtracker.byethost3.com/signin.html');
                    </script>";
                }
            }

            //If the email does not exists in the database
            else{
                echo
                "<script> 
                alert('This email address is not registered yet. Press ok to register through the Sign Up page.');
                window.location.replace('http://moodtracker.byethost3.com/signup.html');
                </script>";
            }
        }
        //If the query to extract data fails
        else{
            echo
            "<script> 
            alert('An error occured while verifying your account. Press ok to try again');
            window.location.replace('http://moodtracker.byethost3.com/signup.html');
            </script>";
        }

    }else{
        echo
        "<script> 
        alert('The url you just used is incomplete. Please check your email address and use the one found there.');
        window.location.replace('http://moodtracker.byethost3.com/signin.html');
        </script>";
    }

    $conn -> close();
?>