<?php
// Start session only if no session has been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("../connection.php");
include_once("../functions.php");

$user_data = check_login($conn);

$num_books = getNumUserBooks($conn);
$book_data = getUserBookData($conn);

// Handling search functionality
$searchResults = [];
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['searchQuery'])) {
    $searchType = $_GET['searchType'];
    $searchQuery = $_GET['searchQuery'];
    $searchResults = searchBooks($conn, $searchType, $searchQuery);
} else {
    // If no search performed, display all books
    $searchResults = getAllBooks($conn);
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['checkout'])) {
    $bookId = $_POST['bookId'];
    $userId = $user_data['id']; // Ensure you have the user's ID

    if (hasUserCheckedOutBook($conn, $userId, $bookId)) {
        echo json_encode(['success' => false, 'message' => 'You have already checked out this book.']);
    } else {
        if (checkoutBook($conn, $userId, $bookId)) {
            // Assume checkoutBook updates the amount and returns the new amount
            $newAmount = getBookAmount($conn, $bookId); // Function to get the new amount
            echo json_encode(['success' => true, 'newAmount' => $newAmount]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to check out the book.']);
        }
    }
    exit();
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
    <link rel="stylesheet" hred="css/explore.css">
    <title>Library home</title>
</head>

<body>
    <div class="container-fluid">
        <!-- Logo -->
        <div class="text-center">
            <img src="../images/myLibraryLogo.JPG" alt="MyLibrary logo">
        </div>
        <!-- navbar -->
        <ul class="nav justify-content-center" style="background-color:#073c6b; margin: 10px; padding: 10px;">
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

        <div class="container">
            <div class="row align-items-center justify-content-between">
                <!-- Search Bar -->
                <div class="col">
                    <form class="d-flex" action="explore.php" method="GET">
                        <input class="form-control me-2" type="search" name="searchQuery" placeholder="Search" aria-label="Search">
                        <!-- Hidden field for search type -->
                        <input type="hidden" name="searchType" id="searchType" value="title">

                        <!-- Search Button -->
                        <button class="btn btn-outline-success" type="submit" style="background-color: #007BFF; border-color: #007BFF; color: white;" onmouseover="this.style.backgroundColor='#66B2FF'" onmouseout="this.style.backgroundColor='#007BFF'">Search</button>

                    </form>
                </div>

                <!-- Dropdown -->
                <div class="col-auto">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" id="dropdownMenuButton1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Search by Title
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#" onclick="updateDropdownLabel('Search by Title', 'title')">Search by Title</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateDropdownLabel('Search by Author', 'author')">Search by Author</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateDropdownLabel('Search by Genre', 'genre')">Search by Genre</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <br>
        <!-- Display search results -->
        <div class="container-fluid">
            <div class="row m-auto mb-4">
                <?php
                if (!empty($searchResults)) {
                    foreach ($searchResults as $key => $book) {
                        if ($key % 4 == 0 && $key != 0) {
                            echo "</div><div class='row m-auto mb-4'>";
                        }
                ?>
                        <div class="col-md-3 mb-4">
                            <div class='card' style='width: 21rem; height: 39rem;'>
                                <img src='<?php echo $book['imgPath']; ?>' class='card-img-top' alt='Book Image' style="height: 350px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class='card-title'><?php echo htmlspecialchars($book['title']); ?></h5>
                                    <p class='card-text'><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                                    <p class='card-text'>Genre: <?php echo htmlspecialchars($book['genre']); ?></p>
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
                                        <div class="col-md-4 justify-content-center">
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#reserveModal<?php echo $key; ?>" <?php echo $book['amount'] != 0 ? 'disabled' : ''; ?>>
                                                Reserve
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
                                                        <button type="button" class="btn btn-primary" onclick="checkoutBook(<?php echo $book['bookId']; ?>);">
                                                            Yes, checkout
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal for reserving books -->
                                    <div class="modal fade" id="reserveModal<?php echo $key; ?>" tabindex="-1" aria-labelledby="reserveModalLabel<?php echo $key; ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="reserveModalLabel<?php echo $key; ?>">Confirm Reservation</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h4>Are you sure you want to reserve <strong><?php echo $book['title']; ?></strong> by <strong><?php echo $book['author']; ?></strong>?</h4>
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="post" action="">
                                                        <input type="hidden" name="bookId" value="<?php echo $book['bookId']; ?>">
                                                        <button type="submit" name="reserve" class="btn btn-primary">Yes, reserve</button>
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
                    echo "<div class='col-12'><p>No results found.</p></div>";
                }
                ?>
            </div>
        </div>


        <!-- footer -->
        <div class="container-fluid" id="companyFooter">
            <!-- Footer -->
            <footer class="text-center text-lg-start text-muted" style="background-color:#073c6b;">
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
    <script src="javascript/explore.js" defer></script>
</body>

</html>