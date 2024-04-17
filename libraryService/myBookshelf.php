<!-- php code -->
<?php
session_start();

include("../connection.php");
include("../functions.php");

$user_data = check_login($conn);

$num_books = getNumUserBooks($conn);
$book_data = getUserBookData($conn);

// if form is submitted call return function
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['return_book'])) {
    $bookId = $_POST['bookId'];
    // successful return
    if (returnBook($conn, $bookId)) {
        echo "<script>alert('Book returned successfully');</script>";
        $num_books = getNumUserBooks($conn);
        $book_data = getUserBookData($conn);
    } else {
        echo "<script>alert('Failed to return book');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/pages.css">
    <title>My bookshelf</title>
</head>

<body>
    <div class="container-fluid">
        <!-- Logo -->
        <div class="text-center">
            <img src="../images/myLibraryLogo.JPG" alt="MyLibrary logo">
        </div>
        <!-- navbar -->
        <ul class="nav justify-content-center" style="background-color: #073c6b;  margin: 10px; padding: 10px;">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="libraryHome.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="myBookshelf.php">My bookshelf</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="explore.php">Explore</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="about.php">About</a>
            </li>
            <li class="nav-item ms-auto">
                <a class="bi bi-person-circle" data-bs-toggle="offcanvas" href="#profilePane" role="button" style="font-size: 2rem; text-decoration: none;">
                    &nbsp<?php echo $user_data['userName'] ?>
                </a>

                <div class="offcanvas offcanvas-end" tabindex="-1" id="profilePane" aria-labelledby="profilePaneLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="profilePaneLabel"><?php echo $user_data['userName'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div>
                            Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                        </div>

                        <a class="btn btn-danger" href="../userAuth/logout.php" role="button" style="margin: auto;">Logout</a>
                    </div>
                </div>
            </li>
        </ul>

        <!-- page content -->
        <h1>You have <?php echo $num_books; ?> book(s) checked out under your name, <?php echo $user_data['userName']; ?></h1>

        <div class="row m-auto mb-4">
            <?php
            // check if book data is available
            if ($book_data) {
                // loop through book record
                foreach ($book_data as $key => $book) {
                    // start new row every four books
                    if ($key % 4 == 0) {
                        echo "</div><div class='row m-auto mb-4'>";
                    }
            ?>
                    <!-- display card with book info -->
                    <div class='col-md-3'>
                        <div class='card' style='width: 21rem; height: 39rem;'>
                            <img src='<?php echo $book['imgPath']; ?>' class='card-img-top' width="auto" height="350px" alt='Book Image'>
                            <div class='card-body'>
                                <h3 class='card-title'><?php echo $book['title']; ?></h3>
                                <p class='card-text'><strong><?php echo $book['author']; ?></strong></p>
                                <p class='card-text'><?php echo $book['genre']; ?></p>
                                <p class='card-text'><u>ISBN:</u> <?php echo $book['isbn']; ?></p>
                                <p class='card-text'><u>Checked out:</u> <?php echo date('D, M d, Y', strtotime($book['dateOfCheckout'])); ?></p>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#returnModal<?php echo $key; ?>">
                                        Return
                                    </button>
                                </div>

                                <!-- Modal for returning books -->
                                <div class="modal fade" id="returnModal<?php echo $key; ?>" tabindex="-1" aria-labelledby="returnModalLabel<?php echo $key; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="returnModalLabel<?php echo $key; ?>">Process return</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h4>Are you sure you want to return <strong><?php echo $book['title']; ?></strong> by <strong><?php echo $book['author']; ?></strong>?</h4>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-success" data-bs-dismiss="modal">No, keep it.</button>
                                                <form method="post">
                                                    <input type="hidden" name="bookId" value="<?php echo $book['bookId']; ?>">
                                                    <button type="submit" name="return_book" class="btn btn-warning">Yes, return my book</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>

        <!-- footer -->
        <div class="container-fluid" id="companyFooter">
            <!-- Footer -->
            <footer class="text-center text-lg-start text-muted" style="background-color: #073c6b; ">
                <!-- Section: Social media -->
                <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
                    <!-- Left -->
                    <div class="me-5 d-none d-lg-block">
                        <span>Stay connected with us:</span>
                    </div>

                    <!-- Right -->
                    <!-- Section: Social media -->
                    <div>
                        <a href="https://github.com/orgs/Web-tech-final-project/repositories" target="_blank" class="me-4 text-reset">
                            <i class="bi bi-github"></i>
                        </a>
                        <a href="https://facebook.com" target="_blank" class="me-4 text-reset">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://x.com" target="_blank" class="me-4 text-reset">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                        <a href="https://instagram.com" target="_blank" class="me-4 text-reset">
                            <i class="bi bi-instagram"></i>
                        </a>
                    </div>
                </section>

                <!-- Section: Links  -->
                <div class="container text-center text-md-start mt-5">
                    <!-- Grid row -->
                    <div class="row mt-3">
                        <!-- Grid column -->
                        <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                            <!-- Content -->
                            <h6 class="text-uppercase fw-bold mb-4">
                                Online catalog
                            </h6>
                            <p>
                                Check out some of these links to view resources and other information.
                            </p>
                        </div>
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold mb-4">
                                Products
                            </h6>
                            <p>
                                <a href="libraryHome.php" class="text-reset">MyLibrary</a>
                            </p>
                        </div>
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold mb-4">
                                Useful links
                            </h6>
                            <p>
                                <a href="about.php" class="text-reset">About</a>
                            </p>
                            <p>
                                <a href="faq.php" class="text-reset">FAQ</a>
                            </p>
                            <p>
                                <a href="help.php" class="text-reset">Help</a>
                            </p>
                        </div>
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
                            <p>
                                <i class="bi bi-house-door-fill"></i>
                                1301 E Main St, <br> Murfreesboro, TN 37132 <br> Middle Tennessee State University
                            </p>
                            <p>
                                <i class="bi bi-envelope"></i>
                                MyLovelyLibrary@gmail.com
                            </p>
                            <p>
                                <i class="bi bi-telephone"></i> +1 615-123-4567
                            </p>
                        </div>
                        <!-- Grid column -->
                    </div>
                    <!-- Grid row -->
                </div>

                <!-- Copyright -->
                <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
                    © 2024 MyLibrary
                </div>
                <!-- Copyright -->
            </footer>
            <!-- Footer -->
        </div>
    </div>

    <!-- link for bootstrap js compatability -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>