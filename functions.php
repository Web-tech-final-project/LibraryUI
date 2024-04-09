<?php

// attempt to authenticate user by userId
function check_login($conn)
{
    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
        $query = "SELECT * FROM users
                  WHERE id = '$id' limit 1";

        $result = mysqli_query($conn, $query);

        // if found return user record (data)
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }

    // redirect to login page if user not found
    header("Location: userAuth/login.php");
    die;
}

// attempt to authenticate user by userId in library service folder
function check_login_libraryService($conn)
{
    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
        $query = "SELECT * FROM users
                  WHERE id = '$id' limit 1";

        $result = mysqli_query($conn, $query);

        // if found return user record (data)
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }

    // redirect to login page if user not found
    header("Location: ../userAuth/login.php");
    die;
}

// getting number of user books
function getUserBooks($conn)
{
    $id = $_SESSION['id'];
    $query = "SELECT COUNT(*) FROM books
              WHERE ownerUserId = '$id'";

    $result = mysqli_query($conn, $query);

    // if successful query return number of books counted
    if ($result) {
        $row = mysqli_fetch_row($result);
        return $row[0];
    }
}

// return user books data
function getBookData($conn)
{
    $id = $_SESSION['id'];
    $query = "SELECT b.*, g.genre
              FROM books b
              LEFT JOIN genres g ON b.genreId = g.genreId
              WHERE b.ownerUserId = '$id'";

    $result = mysqli_query($conn, $query);

    // initialize an empty array to store book data
    $booksData = array();

    // if query was successful get store all records returned
    if ($result) {
        // fetch all rows from the result set
        while ($row = mysqli_fetch_assoc($result)) {
            $booksData[] = $row;
        }

        return $booksData;
    }
}