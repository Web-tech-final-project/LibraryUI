<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

ini_set ("SMTP","mail.gmail.com");
ini_set ("sendmail_from",$_POST['email']);

if (isset($_POST["submit"])) {

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'halocraft2014@gmail.com';
    $mail->Password = 'wrllkjhzgmebsmmn';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = '465';
    
    //$mail->setFrom('halocraft2014@gmail.com');
    $email = $_POST['email'];
    $mail->setFrom($email);
    //$mail->addAddress($_POST['email']);
    $mail->addAddress('halocraft2014@gmail.com');
    $mail->isHTML(true);

    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    //$from = $email;
    //$to = 'angelvazquez042002@gmail.com';

    $mail->Subject = $subject . ': Message from MyLibrary ';
    $mail->Body = 'Customer Name: ' . $name . 
                    '<br>Customer Email: ' . $email
                    . '<br><br>' . $message . '<br><br><b>sent using MyLibrary Help Services</b>';


    /*$body = "From: $name\n";
    $body .= "E-Mail: $email\n";
    $body .= "Subject: $subject\n";
    $body .= "Message: $message\n";

    // Check if name has been entered
    if (!$_POST['name']) {
        $errName = 'Please enter your name';
    }

    // Check if email has been entered and is valid
    if (!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errEmail = 'Please enter a valid email address';
    }

    //Check if message has been entered
    if (!$_POST['info_message']) {
        $errMessage = 'Please enter your message';
    }

    // If there are no errors, send the email
    if (!isset($errName) && !isset($errEmail) && !isset($errMessage)) {
        if (mail($to, $subject, $body, $from)) {
            $mail->send();

            $result = "<div class='alert alert-success'>Thank You! I will be in touch</div>
            <script>document.location.href = 'help.php';</script>";
            echo ($result);
        } else {
            $result = "<div class='alert alert-danger'>Sorry there was an error sending your message. Please try again later</div>
            <script>document.location.href = 'help.php';</script>";
            echo ($result);
        }
    } else {
        echo "<script>alert('Check your fields');
        document.location.href = 'help.php';
        </script>";
    }*/

    $mail->send();
    
    echo 
    "
    <script>
        alert ('Message sent successfully');
        document.location.href = 'help.php';
    </script>
    ";
}
