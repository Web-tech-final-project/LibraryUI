<?php

// imports
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

ini_set ("SMTP","mail.gmail.com");
ini_set ("sendmail_from",$_POST['email']);

// checks something i think
if (isset($_POST["submit"])) {

    // mailer setup
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'halocraft2014@gmail.com';
    $mail->Password = 'wrllkjhzgmebsmmn';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = '465';
    
    // set to/from emails
    $email = $_POST['email'];
    $mail->setFrom('halocraft2014@gmail.com');
    $mail->addAddress('halocraft2014@gmail.com');
    $mail->isHTML(true);

    // get user info
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // build/send message to MyLibrary
    $mail->Subject = $subject . ': Message from MyLibrary ';
    $mail->Body = 'User Name: ' . $name . 
                    '<br>User Email: ' . $email
                    . '<br><br>' . $message . '<br><br><b>sent using MyLibrary Help Services</b>';
    $mail->send();

    // clear recipient address
    $mail->clearAddresses();

    // build/send message to user
    $mail->addAddress($_POST['email']);
    $mail->Subject = $subject . ': Message from MyLibrary ';
    $mail->Body = 'Thank you, your request has been received. <br> Please allow 48 hours for us to respond.<br>'
                    . '<br><br><b>sent using MyLibrary Help Services</b>';
    $mail->send();

    // display successful email
    echo 
    "
    <script>
        alert ('Message sent successfully');
        document.location.href = 'help.php';
    </script>
    ";
} // end
