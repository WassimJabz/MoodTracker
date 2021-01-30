<?php

    //Including phpmailer for sending emails
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

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

    //Extracting the email and the password (NOT PASSWORD AS THAT WOULD BE THE SERVER'S)
    $useremail = $_POST["email"];
    $userpassword = $_POST["password"];

    //Checking that the email exists in the database
    $emailquery = "SELECT * FROM logtable WHERE email = '$useremail'";
    $emailresult = mysqli_query($conn, $emailquery);

    //If the query to extract data is successful
    if($emailresult){

        //If the email exists in the database
        if(mysqli_num_rows($emailresult) > 0){
            
            //Fetching the data as an array to check the conditions
            $userarray = mysqli_fetch_assoc($emailresult);  
            
            //If the passwords match, check the verified field
            if(password_verify($userpassword, $userarray["pass"])){
                
                //If the user is verified, he can log in
                if($userarray["verified"] == 1){

                    //Creating a session
                    session_start();
                    
                    //Setting the session variables
                    $_SESSION['email'] = $useremail;
                    $_SESSION['loggedin'] = 1;

                    //Redirecting to the home page
                    header('Location: http://moodtracker.byethost3.com/home.html');
                }

                //If the account is not verified yet
                else{

                    //Sending the verification email using PHPMailer
                    $mail = new PHPMailer(true);

                    try {
                        // Server settings
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;
                        $mail->Username = 'moodtrackerproject@gmail.com';
                        $mail->Password = 'Pass12345@';

                        // Sender and recipient settings
                        $mail->setFrom('moodtrackerproject@gmail.com', 'Mood Tracker');
                        $mail->addAddress($useremail, 'Mood Tracker Users');
                        $mail->addReplyTo('moodtrackerproject@gmail.com', 'Mood Tracker'); // to set the reply to

                        // Setting the email content
                        $mail->Subject = "Mood tracker account verification";
                        $mail->Body = '

                        Please verify your account using the link below before signing in:
                        
                            http://moodtracker.byethost3.com?email='.$useremail.'&token='.$userarray['token'].'

                        Have fun mood tracking!

                        ';

                        $mail->send();

                        //Printing directives for the user to follow and redirecting to the Login page
                        echo
                        "<script> 
                        alert('Please verify your email address before logging in. A new verification email has been sent, please check your JUNK folder.');
                        window.location.replace('http://moodtracker.byethost3.com/signin.html');
                        </script>";

                    } catch (Exception $e) {
                        echo
                        "<script> 
                        alert('The verification email could not be sent. Please try logging in to receive a new one.');
                        window.location.replace('http://moodtracker.byethost3.com/signin.html');
                        </script>";
                    }
                }
            }

            //If the password is not correct
            else{
                echo
                "<script> 
                alert('The password does not match. Press ok to try logging in again.');
                window.location.replace('http://moodtracker.byethost3.com/signin.html');
                </script>";
            }
        }

        //If the email doesn't exist in the database
        else{
            echo
            "<script> 
            alert('This email does not have an account associated with it. Press ok to go to the sign up page.');
            window.location.replace('http://moodtracker.byethost3.com/signup.html');
            </script>";
        }
    }

    //If searching the database fails for some unknown reason
    else{
        echo
        "<script> 
        alert('An error occured while retrieving data from the server. Press ok to try logging in again.');
        window.location.replace('http://moodtracker.byethost3.com/signin.html');
        </script>";
    }

    //Ending the connexion
    $conn->close();

?>