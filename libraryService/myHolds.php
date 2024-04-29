<?php
session_start();

include("../connection.php");
include("../functions.php");

$user_data = check_login($conn);

$num_reserves = getNumUserReserves($conn);
$reserve_data = getUserReserveData($conn);

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['checkout'])) {
    $bookId = $_POST['bookId'];
    $userId = $user_data['id'];

    if (checkoutReservedBook($conn, $userId, $bookId)) {
        $num_reserves = getNumUserReserves($conn);
        $reserve_data = getUserReserveData($conn);
        echo "<script>alert('Book checked out successfully');</script>";
    } else {
        echo "<script>alert('Failed to checkout book.');</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['remove_hold'])) {
    $bookId = $_POST['bookId'];
    $userId = $user_data['id'];

    if (removeReservedBook($conn, $userId, $bookId)) {
        $num_reserves = getNumUserReserves($conn);
        $reserve_data = getUserReserveData($conn);
        echo "<script>alert('Removed hold successfully');</script>";
    } else {
        echo "<script>alert('Failed to remove hold.');</script>";
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
    <title>My Holds</title>
</head>

<body>
    <div class="container-fluid">
        <!-- Logo -->
        <div class="text-center">
            <img src="../images/myLibraryLogo.JPG" alt="MyLibrary logo">
        </div>
        <!-- navbar -->
        <ul class="nav justify-content-center" style="background-color: #073c6b; margin: 10px; padding: 10px;">
            <li class="nav-item">
                <a class="nav-link" href="myHolds.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="myBookshelf.php">My Bookshelf</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="myHolds.php">My Holds</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="explore.php">Explore</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="bookFees.php">Book Fees</a>
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
                        <h5 class="offcanvas-title" id="profilePaneLabel"><span style="color: blue;"><?php echo $user_data['userName'] ?></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div>
                            <?php echo "Date joined: " . date('Y-m-d', strtotime($user_data['date'])); ?>
                            <br><br>
                        </div>

                        <a class="btn btn-danger" href="../userAuth/logout.php" role="button" style="margin: auto;">Logout</a>
                    </div>
                </div>
            </li>
        </ul>

        <!-- page content -->
        <h1 class="text-center"><?php echo ($num_reserves > 0) ? "You have $num_reserves book(s) reserved, {$user_data['userName']}." : "<a href='explore.php'>Explore</a> our book collection and make some reservations."; ?></h1>

        <div class="row m-auto mb-4">
            <?php
            if ($reserve_data) {
                foreach ($reserve_data as $key => $book) {
                    if ($key % 4 == 0) {
                        echo "</div><div class='row m-auto mb-4'>";
                    }
            ?>
                    <div class='col-md-3'>
                        <div class='card' style='width: 21rem; height: 39rem;'>
                            <img src='<?php echo $book['imgPath']; ?>' class='card-img-top' width='auto' height='350px' alt='Book Image'>
                            <div class='card-body'>
                                <h5 class='card-title'><?php echo $book['title']; ?></h5>
                                <p class='card-text'><strong><?php echo $book['author']; ?></strong></p>
                                <p class='card-text'><?php echo $book['genre']; ?></p>
                                <p class='card-text'>ISBN: <?php echo htmlspecialchars($book['isbn']); ?></p>
                                <p class='card-text'>Amount Available: <span id="amountAvailable<?php echo $book['bookId']; ?>"><?php echo htmlspecialchars($book['amount']); ?></span></p>

                                <!-- Checkout Button Trigger for Modal -->
                                <div class="row justify-content-center">
                                    <div class="col-md-4 justify-content-center">
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#checkoutModal<?php echo $key; ?>" <?php echo $book['amount'] == 0 ? 'disabled' : ''; ?>>
                                            Checkout
                                        </button>
                                    </div>
                                    <!-- Reserve Button Trigger for Modal -->
                                    <div class="col-auto justify-content-center">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#removeHoldModal<?php echo $key; ?>">
                                            Remove hold
                                        </button>
                                    </div>
                                </div>

                                <!-- Modal for checking out books -->
                                <div class="modal fade" id="checkoutModal<?php echo $key; ?>" tabindex="-1" aria-labelledby="checkoutModalLabel<?php echo $key; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="checkoutModalLabel<?php echo $key; ?>">Confirm Checkout</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h4>Are you sure you want to checkout <strong><?php echo $book['title']; ?></strong> by <strong><?php echo $book['author']; ?></strong>?</h4>
                                            </div>
                                            <div class="modal-footer">
                                                <form method="post" action="">
                                                    <input type="hidden" name="bookId" value="<?php echo $book['bookId']; ?>">
                                                    <button type="submit" name="checkout" class="btn btn-primary">
                                                        Yes, checkout
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal for removing a hold -->
                                <div class="modal fade" id="removeHoldModal<?php echo $key; ?>" tabindex="-1" aria-labelledby="removeHoldModalLabel<?php echo $key; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="removeHoldModalLabel<?php echo $key; ?>">Confirm Checkout</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h4>Are you sure you want to remove hold for <strong><?php echo $book['title']; ?></strong> by <strong><?php echo $book['author']; ?></strong>?</h4>
                                            </div>
                                            <div class="modal-footer">
                                                <form method="post" action="">
                                                    <input type="hidden" name="bookId" value="<?php echo $book['bookId']; ?>">
                                                    <button type="submit" name="remove_hold" class="btn btn-primary">
                                                        Yes, remove hold
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p class='text-center'>You have no books currently reserved.</p>";
            }
            ?>
        </div>

        <!-- footer -->
        <div class="container-fluid" id="companyFooter">
            <!-- Footer -->
            <footer class="text-center text-lg-start text-muted" style="background-color: #073c6b;">
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