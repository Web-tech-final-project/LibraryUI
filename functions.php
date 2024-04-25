<?php

// attempt to authenticate user by userId
function check_login($conn)
{
    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
        $query = "SELECT * FROM users
                  WHERE id = '$id' AND isDeleted IS NULL limit 1";

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
                        SET newReturnBy = DATE_ADD(CURRENT_DATE, INTERVAL 1 WEEK), renewalDate = CURRENT_DATE
                        WHERE bookId = '$bookId' AND userId = '$id' AND dateOfReturn IS NULL";

    $resultExtend = mysqli_query($conn, $queryExtendDate);

    // if date extended successfully
    if ($resultExtend) {
        return true;
    }

    return false;
}

function numOverdueRentals($conn)
{
    $id = $_SESSION['id'];

    $numOverdueQuery = "SELECT COUNT(*) FROM rentals
                        WHERE userId = '$id' AND overdueFee IS NOT NULL";

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
    // books that haven't been renewed or returned
    $updateNotRenewedNotReturnedQuery = "UPDATE rentals
                     SET overdueFee = DATEDIFF(CURRENT_DATE, returnBy) * 0.10
                     WHERE dateOfReturn IS NULL AND renewalDate IS NULL AND CURRENT_DATE > returnBy AND payDate IS NULL";
    mysqli_query($conn, $updateNotRenewedNotReturnedQuery);

    // books that haven't been renewed but have been returned
    $updateNotRenewedReturnedQuery = "UPDATE rentals
                     SET overdueFee = DATEDIFF(dateOfReturn, returnBy) * 0.10
                     WHERE dateOfReturn > returnBy AND renewalDate IS NULL AND payDate IS NULL";
    mysqli_query($conn, $updateNotRenewedReturnedQuery);

    // books that have been renewed and aren't overdue after renewal period
    $updateRenewedQuery = "UPDATE rentals
                     SET overdueFee = DATEDIFF(renewalDate, returnBy) * 0.10
                     WHERE (dateOfReturn IS NULL OR dateOfReturn > newReturnBy) AND renewalDate IS NOT NULL AND CURRENT_DATE < newReturnBy AND payDate IS NULL";
    mysqli_query($conn, $updateRenewedQuery);

    // books that have been renewed and are overdue after renewal period
    $updateRenewedOverdueQuery = "UPDATE rentals
                     SET overdueFee = (DATEDIFF(CURRENT_DATE, newReturnBy) + DATEDIFF(renewalDate, returnBy)) * 0.10
                     WHERE (dateOfReturn IS NULL OR dateOfReturn > newReturnBy) AND renewalDate IS NOT NULL AND CURRENT_DATE >= newReturnBy AND payDate IS NULL";
    mysqli_query($conn, $updateRenewedOverdueQuery);

    // books that haven't been renewed and are overdue after being paid for
    $updateNotRenewedPaidQuery = "UPDATE rentals
                     SET overdueFee = DATEDIFF(CURRENT_DATE, payDate) * 0.10
                     WHERE dateOfReturn IS NULL AND payDate IS NOT NULL AND renewalDate IS NULL AND CURRENT_DATE > payDate";
    mysqli_query($conn, $updateNotRenewedPaidQuery);

    // books that have been renewed and are overdue after being paid for
    $updateRenewedPaidQuery = "UPDATE rentals
                     SET overdueFee = DATEDIFF(CURRENT_DATE, payDate) * 0.10
                     WHERE dateOfReturn IS NULL AND payDate IS NOT NULL AND renewalDate IS NOT NULL AND CURRENT_DATE > payDate AND CURRENT_DATE > newReturnBy";
    mysqli_query($conn, $updateRenewedPaidQuery);

    // books that have been paid for, then became overdue again before returning
    $updateReturnedPaidQuery = "UPDATE rentals
                     SET overdueFee = DATEDIFF(dateOfReturn, payDate) * 0.10
                     WHERE dateOfReturn IS NOT NULL AND payDate IS NOT NULL AND dateOfReturn > payDate";
    mysqli_query($conn, $updateReturnedPaidQuery);

    $overdueQuery = "SELECT b.*, r.*
                     FROM books b
                     JOIN rentals r ON b.bookId = r.bookId
                     WHERE r.userId = '$id' AND r.overdueFee IS NOT NULL";

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
              WHERE userId = '$id' AND overdueFee IS NOT NULL";

    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row['SUM(overdueFee)'];
    } else {
        return 0; // Or handle no rows case differently
    }
}

// pay off price of one overdue rental
function payOneBook($conn, $rentalId)
{
    $query = "UPDATE rentals
              SET payDate = CURRENT_DATE, overdueFee = NULL
              WHERE rentalId = '$rentalId' AND overdueFee IS NOT NULL";

    $result = mysqli_query($conn, $query);


    if ($result) {
        return true;
    } else {
        return false;
    }
}

function payAllBooks($conn)
{
    $id = $_SESSION['id'];

    $query = "UPDATE rentals
              SET payDate = CURRENT_DATE, overdueFee = NULL
              WHERE userId = '$id' AND overdueFee IS NOT NULL";

    $result = mysqli_query($conn, $query);


    if ($result) {
        return true;
    } else {
        return false;
    }
}
