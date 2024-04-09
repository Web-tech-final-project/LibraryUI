<?php

session_start();

// check if user is logged in then log them out
if (isset($_SESSION['id']))
{
    unset($_SESSION['id']);
}

// redirect to login page
header("Location: login.php");
die;