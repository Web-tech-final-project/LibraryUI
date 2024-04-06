<?php

session_start();

// check if user is logged in then log them out
if (isset($_SESSION['userId']))
{
    unset($_SESSION['userId']);
}

// redirect to login page
header("Location: login.php");
die;