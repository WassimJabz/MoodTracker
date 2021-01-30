<?php

    //Including phpmailer for sending emails
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
   
	
    if($_POST['password'] != $_POST['confpassword']){
        echo
            "<script> 
            alert('The two passwords do not match. Please try again.');
            window.location.replace('http://moodtracker.byethost3.com/signup.html');
            </script>";
    } 

    else if(strlen($_POST["password"]) < 6){
        echo
            "<script> 
            alert('Your password should be at least 6 characters.');
            window.location.replace('http://moodtracker.byethost3.com/signup.html');
            </script>";
    }

    else if(!strpos($_POST['email'], "@")){
        echo
            "<script> 
            alert('Not a valid email address. Please try again.');
            window.location.replace('http://moodtracker.byethost3.com/signup.html');
            </script>";
    }
    
    //If all conditions are met
    else if($_POST['password'] == $_POST['confpassword'] && strlen($_POST["password"]) >= 6 
        && strpos($_POST['email'], "@")){

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

        //Generating a unique random token
        $token = md5(uniqid(rand(), true));
        
        //Hashing the user's pass
        $hash = password_hash($userpassword, PASSWORD_DEFAULT);

        //Inserting the data into our database
        $insertquery = "INSERT INTO logtable (email, pass, token)
        VALUES ('$useremail', '$hash', '$token')";
        $insertresult = mysqli_query($conn, $insertquery);

        //Checking if the insertion was successful
        if ($insertresult){ 
            
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
                $mail->addAddress($useremail, 'Mood Tracker User');
                $mail->addReplyTo('moodtrackerproject@gmail.com', 'Mood Tracker'); // to set the reply to

                // Setting the email content
                $mail->Subject = "Mood Tracker account verification";
                $mail->Body = '

                Thank you for signing up to Mood Tracker!

                Your account has been created, you can login after you have activated your account by pressing the url below:
                http://moodtracker.byethost3.com/activate.php?email='.$useremail.'&token='.$token.'

                Have fun Mood Tracking!';

                $mail->send();

                echo
                "<script> 
                alert('Thanks for signing up! A verification email will shortly be sent to your email address (PLEASE CHECK YOUR JUNK FOLDER !)');
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

        //If it wasn't successful because of a duplicate primary key
        else if(mysqli_errno($conn) == 1062){
            echo 
            "<script> alert('This email already has an account associated with it. Press ok to proceed to the Login page from which you can sign in.');
            window.location.replace('http://moodtracker.byethost3.com/signin.html');
            </script>";
        }

        //If it wasn't successful for some other reason
        else{
            echo
            "<script> 
            alert('An error occured while creating your account. Press ok to try signing up again.');
            window.location.replace('http://moodtracker.byethost3.com/signup.html');
            </script>";
        }

        //Ending the connexion
        $conn->close();

    }
?>