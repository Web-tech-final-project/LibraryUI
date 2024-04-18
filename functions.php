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
    header("Location: ../userAuth/login.php");
    die;
}

// getting number of user books
function getNumUserBooks($conn)
{
    $id = $_SESSION['id'];
    $query = "SELECT COUNT(*) FROM rentals
              WHERE userId = '$id' AND dateOfReturn IS NULL";

    $result = mysqli_query($conn, $query);

    // if successful query return number of books counted
    if ($result) {
        $row = mysqli_fetch_row($result);
        return $row[0];
    }
}

// return user books data
function getUserBookData($conn)
{
    $id = $_SESSION['id'];
    $query = "SELECT b.*, g.genre, bi.imgPath, r.*
              FROM books b
              LEFT JOIN genres g ON b.genreId = g.genreId
              LEFT JOIN bookImgs bi ON b.imgId = bi.imgId
              JOIN rentals r ON b.bookId = r.bookId
              WHERE r.userId = '$id' AND r.dateOfReturn IS NULL";

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

// function to return a book and update databse
function returnBook($conn, $bookId)
{
    $id = $_SESSION['id'];

    // query to update rentals table
    $queryRentals = "UPDATE rentals
              SET dateOfReturn = NOW()
              WHERE bookId = '$bookId' AND userId = '$id' AND dateOfReturn IS NULL";

    $resultRentals = mysqli_query($conn, $queryRentals);

    // if rentals result was successful
    if ($resultRentals) {
        // query to update books table amount
        $queryBooks = "UPDATE books
                       SET amount = amount + 1
                       WHERE bookId = '$bookId'";

        $resultBooks = mysqli_query($conn, $queryBooks);

        // if books query was successful
        if ($resultBooks) {
            return true;
        }
    }
    return false;
}

// function to renew books and update rentals table
function renewBook($conn, $bookId)
{
    $id = $_SESSION['id'];

    // query to update rentals table
    $queryExtendDate = "UPDATE rentals
                        SET newReturnBy = DATE_ADD(returnBy, INTERVAL 1 WEEK)
                        WHERE bookId = '$bookId' AND userId = '$id' AND dateOfReturn IS NULL AND isRenewed = 0";

    $resultExtend = mysqli_query($conn, $queryExtendDate);

    // if date extended successfully
    if ($resultExtend) {
        $queryMakeRenewed = "UPDATE rentals
                             SET isRenewed = 1
                             WHERE bookId = '$bookId' AND userId = '$id' AND isRenewed = 0";

        $resultMakeRenewed = mysqli_query($conn, $queryMakeRenewed);

        if ($resultMakeRenewed) {
            return true;
        }
    }

    return false;
}
