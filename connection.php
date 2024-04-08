<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "mylibrary";

// new connection to DB
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// connection error message
if ($conn->connect_error)
{
    die("Failed to connect to mylibrary db.");
}