<?php

// attempt to authenticate user by userId
function check_login($conn)
{
    if (isset($_SESSION['userId']))
    {
        $id = $_SESSION['userId'];
        $query = "SELECT * FROM users
                  WHERE userId = '$id' limit 1";

        $result = mysqli_query($conn, $query);

        // if found return user record (data)
        if ($result && mysqli_num_rows($result) > 0)
        {
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }

    // redirect to login page if user not found
    header("Location: userAuth/login.php");
    die;
}

// generating random number for new userId
function random_num($length)
{
    // variable to store id
    $text = "";

    // generate number of random length
    $len = rand(7, $length);
    for ($i=0; $i < $len; $i++) 
    { 
        $text .= rand(0, 9);
    }

    return $text;
}