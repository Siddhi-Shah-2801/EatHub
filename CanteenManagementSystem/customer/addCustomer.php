<?php
//For inserting new customer to database
use PHPMailer\PHPMailer\PHPMailer;
require 'phpmailer\src\PHPMailer.php';
require 'phpmailer\src\Exception.php';
require 'phpmailer\src\SMTP.php';

include('../connectionDB.php');

$pwd = $_POST["pwd"];
$cfpwd = $_POST["cfpwd"];
if ($pwd != $cfpwd) {
    ?>
    <script>
        alert('Your password is not match.\nPlease enter it again.');
        history.back();
    </script>
    <?php
    exit(1);
} else {
    $username = $_POST["username"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $gender = $_POST["gender"];
    $email = $_POST["email"];
    $type = $_POST["type"];

    if ($gender == "-" || $type == "-") {
        ?>
        <script>
            alert('You didn\'t select your gender or role yet.\nPlease select again!');
            history.back();
        </script>
        <?php
        exit(1);
    }

    //Check for duplicating username
    $query = "SELECT customerUserName FROM customer WHERE customerUserName = '$username';";
    $result = $mysqli->query($query);
    if ($result->num_rows >= 1) {
        ?>
        <script>
            alert('Your username is already taken!');
            history.back();
        </script>
        <?php
        exit(1);
    }
    $result->free_result();

    //Check for duplicating email
    $query = "SELECT customerEmail FROM customer WHERE customerEmail = '$email';";
    $result = $mysqli->query($query);
    if ($result->num_rows >= 1) {
        ?>
        <script>
            alert('Your email is already in use!');
            history.back();
        </script>
        <?php
        exit(1);
    } 
    $result->free_result();


        $query = "INSERT INTO customer (customerUsername,customerPassword,customerFirstName,customerLastName,customerEmail,customerGender,customerType)
        VALUES ('$username','$pwd','$firstname','$lastname','$email','$gender','$type');";

        $result = $mysqli->query($query);

        if ($result) 
        {
            if (!isset($_POST["sendMail"])) 
            {

                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->SMTPAuth = true;
                $mail->Username = "siddhi.ds@somaiya.edu";
                $mail->Password = "jvfybwomgggbarhr";
                $mail->SMTPSecure = "ssl";
                $mail->Port = 465;

                $mail->setFrom("siddhi.ds@somaiya.edu");
                $mail->addAddress($_POST["email"]);
                $mail->Subject = "Successfull Registration";
                $mail->Body = "Congratulations, You have successfully Registered";



                if ($mail->send()) {

                    echo "<script>alert('Email sent successfully')

              </script>";

                } else {
                    echo "<script>alert('Email sending failed: " . $mail->ErrorInfo . "')</script>";
                }



            }
            header("location: customerRegisterSuccess.php");
        } else {
            header("location: customerRegisterFail.php?err={$mysqli->errno}");
        }
    }
?>