<?php
// submit_review.php
// Start session only if no session has been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("../connection.php");
include_once("../functions.php");


// Check login status and retrieve user data
$user_data = check_login($conn);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    echo "<script>console.log('This is a console log message');</script>";
    // Retrieve review data from the form
    $bookId = $_POST['book_id'];
    $userId = $user_data['id']; 
    $title = $_POST['review_title'];
    $rating = $_POST['rating'];
    $reviewText = $_POST['review_text'];
    echo "<script>console.log('{$rating}');</script>";

    // Insert review into the database
    $sql = "INSERT INTO reviews (book_id, user_id, title, rating, review_text) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisis", $bookId, $userId, $title, $rating, $reviewText);
    $stmt->execute();

    // Check if the review was successfully inserted
    if ($stmt->affected_rows > 0) {
        // Review inserted successfully, you can redirect to a success page or perform any other action
        header("Location: reviews.php");
        exit();
    } else {
        // Error occurred while inserting review
        // You can redirect back to the form with an error message or perform any other action
        header("Location: explore.php?error=1");
        exit();
    }
} else {
    // If the form wasn't submitted, redirect back to the form page
    header("Location: explore.php");
    exit();
}