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


function searchBooks($conn, $searchType, $searchQuery)
{
    $likeQuery = '%' . $searchQuery . '%';
    $baseSql = "SELECT b.*, g.genre, bi.imgPath 
                FROM books b 
                JOIN genres g ON b.genreId = g.genreId 
                JOIN bookImgs bi ON b.imgId = bi.imgId ";

    if ($searchType == 'title') {
        $sql = $baseSql . "WHERE b.title LIKE ?";
    } elseif ($searchType == 'author') {
        $sql = $baseSql . "WHERE b.author LIKE ?";
    } elseif ($searchType == 'genre') {
        $sql = $baseSql . "WHERE g.genre LIKE ?";
    } else {
        return false; // Invalid search type
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();
    $books = [];
    while ($book = $result->fetch_assoc()) {
        $books[] = $book;
    }
    $stmt->close();
    return $books;
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

function numOverdueRentals($conn)
{
    $id = $_SESSION['id'];

    $numOverdueQuery = "SELECT COUNT(*) FROM rentals
                        WHERE userId = '$id' AND overdueFee IS NOT NULL AND dateOfReturn IS NULL";

    $result = mysqli_query($conn, $numOverdueQuery);

    if ($result) {
        $row = mysqli_fetch_row($result);
        return $row[0];
    }
}

// method to calculate overdue fees
function overdueRentals($conn)
{
    $id = $_SESSION['id'];

    // update db by calculating overdue amount
    // books that haven't been renewed
    $updateNotRenewedQuery = "UPDATE rentals
                     SET overdueFee = DATEDIFF(CURRENT_DATE, returnBy) * 0.10
                     WHERE dateOfReturn IS NULL AND isRenewed = 0 AND CURRENT_DATE > returnBy";
    mysqli_query($conn, $updateNotRenewedQuery);

    // books that have been renewed
    $updateRenewedQuery = "UPDATE rentals
                     SET overdueFee = DATEDIFF(CURRENT_DATE, newReturnBy) * 0.10
                     WHERE dateOfReturn IS NULL AND isRenewed = 1 AND CURRENT_DATE > newReturnBy";
    mysqli_query($conn, $updateRenewedQuery);

    $overdueQuery = "SELECT b.*, r.*
                     FROM books b
                     JOIN rentals r ON b.bookId = r.bookId
                     WHERE r.userId = '$id' AND r.dateOfReturn IS NULL AND r.overdueFee IS NOT NULL";

    $result = mysqli_query($conn, $overdueQuery);

    $overdueBooksData = array();

    if ($result) {
        // fetch all rows from the result set
        while ($row = mysqli_fetch_assoc($result)) {
            $overdueBooksData[] = $row;
        }

        return $overdueBooksData;
    }
}

// returns total fees for all overdue rentals 
function totalFees($conn)
{
    $id = $_SESSION['id'];

    // query to get total fees accrued
    $query = "SELECT SUM(overdueFee)
              FROM rentals
              WHERE userId = '$id' AND dateOfReturn IS NULL AND overdueFee IS NOT NULL";

    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row['SUM(overdueFee)'];
    } else {
        return 0; // Or handle no rows case differently
    }
}
