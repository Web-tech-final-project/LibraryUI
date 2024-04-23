<!-- php code -->
<?php
// Start session only if no session has been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("../connection.php");
include_once("../functions.php");

$user_data = check_login($conn);

$num_overdue_books = numOverdueRentals($conn);
$overdue_books = overdueRentals($conn);
$totalFeesAccrued = totalFees($conn); 
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
        <h1> <?php echo $user_data['userName']; ?>, you have <?php echo $num_overdue_books; ?> overdue book(s) totaling $<?php echo number_format($totalFeesAccrued, 2, '.', ''); ?>. </h1>
        <?php
        if ($num_overdue_books > 0) {
        ?>
            <h1>Please return or renew them at your earliest convenience.</h1>
        <?php
        }
        ?>

        <!-- page content table -->
        <?php
        // check if book data is available
        if ($overdue_books) {
        ?>
            <table class="table table-primary table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Days Overdue</th>
                        <th>Fee</th>
                    </tr>
                </thead>
                <?php
                // loop through book record
                foreach ($overdue_books as $key => $book) {
                    $returnByDate = $book['isRenewed'] == 1 ? $book['newReturnBy'] : $book['returnBy'];
                ?>
                    <tr>
                        <td><?php echo $key + 1; ?></td>
                        <td><?php echo $book['title']; ?></td>
                        <td><?php echo $book['author']; ?></td>
                        <td>
                            <?php echo (strtotime(date('Y-m-d')) - strtotime($returnByDate)) / (60 * 60 * 24); ?>
                        </td>
                        <td>$<?php echo number_format($book['overdueFee'], 2, '.', ''); ?></td>
                    </tr>
            </table>
    <?php
                }
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