<!-- php code -->
<?php
// Start session only if no session has been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("../connection.php");
include_once("../functions.php");

$user_data = check_login($conn);

$overdue_books = overdueRentals($conn);
$num_overdue_books = numOverdueRentals($conn);
$totalFeesAccrued = totalFees($conn);

// if form is submitted to call pay 1 book function
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pay_book'])) {
    $rentalId = $_POST['rentalId'];
    // successful return
    if (payOneBook($conn, $rentalId)) {
        echo "<script>alert('Rental fee paid successfully');</script>";
        $overdue_books = overdueRentals($conn);
        $num_overdue_books = numOverdueRentals($conn);
        $totalFeesAccrued = totalFees($conn);
    } else {
        echo "<script>alert('Payment unsuccessful');</script>";
    }
}

// if form is submitted to call pay all books function
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pay_all_books'])) {
    // successful return
    if (payAllBooks($conn)) {
        echo "<script>alert('Overdue rentals paid successfully');</script>";
        $overdue_books = overdueRentals($conn);
        $num_overdue_books = numOverdueRentals($conn);
        $totalFeesAccrued = totalFees($conn);
    } else {
        echo "<script>alert('Payment unsuccessful');</script>";
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
    <title>Overdue fees</title>
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
                <a class="nav-link" href="myBookshelf.php">My Bookshelf</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="myHolds.php">My Holds</a>
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
        <h1 class="text-center"> <?php echo $user_data['userName']; ?>, you have <?php echo $num_overdue_books; ?> overdue book(s)<?php echo ($num_overdue_books > 0) ? " totaling $" . number_format($totalFeesAccrued, 2, '.', '') : ''; ?>. </h1>
        <?php
        if ($num_overdue_books > 0) {
        ?>
            <h1 class="text-center">Please pay for, <a href="myBookshelf.php">return, and/or renew</a> them at your earliest convenience.</h1>
        <?php
        }
        ?>

        <br>

        <!-- page content table -->
        <?php
        // check if book data is available
        if ($overdue_books) {
        ?>
            <table class="table table-striped table-hover">
                <thead style="text-align: center;">
                    <tr>
                        <th>No.</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Days Overdue</th>
                        <th>Fee</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <?php
                $count = 1;
                // loop through book record
                foreach ($overdue_books as $key => $book) {
                    $returnByDate = $book['renewalDate'] != NULL ? $book['newReturnBy'] : $book['returnBy'];
                    $returned = false;
                    if ($book['dateOfReturn'] != NULL) {
                        $returned = true;
                    }
                    if ($book['overdueFee'] != NULL) {
                ?>
                        <tr style="height: 50px; text-align: center;">
                            <td><?php echo $count; ?></td>
                            <td><?php echo $book['title']; ?></td>
                            <td><?php echo $book['author']; ?></td>
                            <td>
                                <?php
                                if ($returned) {
                                    echo "Returned: " . date('D, M d, Y', strtotime($book['dateOfReturn']));
                                } elseif ($book['payDate'] != NULL && (strtotime(date('Y-m-d')) > strtotime($book['payDate']))) {
                                    echo "Last paid: " . date('D, M d, Y', strtotime($book['payDate']));
                                } elseif ($book['renewalDate'] != NULL && date('Y-m-d') > date('Y-m-d', strtotime($returnByDate))) {
                                    echo ((strtotime(date('Y-m-d')) - strtotime($returnByDate)) + (strtotime($book['renewalDate']) - strtotime($book['returnBy']))) / (60 * 60 * 24) . " days";
                                } elseif ($book['renewalDate'] != NULL) {
                                    echo (strtotime($book['renewalDate']) - strtotime($book['returnBy'])) / (60 * 60 * 24) . " days";
                                } else {
                                    echo (strtotime(date('Y-m-d')) - strtotime(date('Y-m-d', strtotime($returnByDate)))) / (60 * 60 * 24) . " days";
                                }
                                ?>
                            </td>
                            <td>$<?php echo number_format($book['overdueFee'], 2, '.', ''); ?></td>
                            <td>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#payOneModal<?php echo $key; ?>">
                                    <i class="bi bi-credit-card-fill"></i>
                                </button>

                            </td>
                        </tr>

                        <!-- Modal for paying 1 book -->
                        <div class="modal fade" id="payOneModal<?php echo $key; ?>" tabindex="-1" aria-labelledby="payOneModalLabel<?php echo $key; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="payOneModalLabel<?php echo $key; ?>">Process fee payment</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h4>Would you like to pay <strong><?php echo "$" . number_format($book['overdueFee'], 2, '.', '') . " for " . $book['title']; ?></strong> by <strong><?php echo $book['author']; ?></strong>?</h4>
                                        <br>
                                        <h6><span style="color: red;">*</span> This action can't be undone <span style="color: red;">*</span></h6>
                                    </div>
                                    <div class="modal-footer">
                                        <form method="post">
                                            <input type="hidden" name="rentalId" value="<?php echo $book['rentalId']; ?>">
                                            <button type="submit" name="pay_book" class="btn btn-success">Yes, pay now</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                        $count++;
                    }
                }
                ?>
            </table>

            <div class="row justify-content-center">
                <div class="col-md-auto">
                    <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#payAllModal">
                        Pay all &nbsp;<i class="bi bi-credit-card-fill"></i>
                    </button>
                </div>
            </div>

            <!-- Modal for returning books -->
            <div class="modal fade" id="payAllModal" tabindex="-1" aria-labelledby="payAllModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="payAllModalLabel">Process fee payment(s)</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h4>Would you like to pay <strong><?php echo "$" . number_format($totalFeesAccrued, 2, '.', '') . " "; ?></strong> for <strong><?php echo $num_overdue_books ?></strong> books?</h4>
                            <br>
                            <h6><span style="color: red;">*</span> This action can't be undone <span style="color: red;">*</span></h6>
                        </div>
                        <div class="modal-footer">
                            <form method="post">
                                <button type="submit" name="pay_all_books" class="btn btn-success">Yes, pay all now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        <?php
        }
        ?>

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