<!-- php code -->
<?php
session_start();

include("../connection.php");
include("../functions.php");
require_once("../libraryService/libraries.php"); 
$library = new libraries(); // Create a new instance of the libraries class
$user_data = check_login($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
 
	<script type="text/javascript" src="javascript/googlemap.js"></script>
    <link rel="stylesheet" href="css/pages.css">
    <link rel="stylesheet" href="css/Home.css">
    
    <style type="text/css">
		.map-container {
        height: 450px;
    }
		#map {
			width: 100%;
			height: 100%;
			border: 1px solid blue;
		}
		#data, #allData {
			display: none;
		}
	</style>
    <title>MyLibrary home</title>
</head>

<body>
    <div class="container-fluid">
        <!-- Logo -->
        <div class="text-center">
            <img src="../images/myLibraryLogoEdited.PNG" alt="MyLibrary logo">
        </div>

        <!-- navbar -->
        <ul class="nav justify-content-center" style="background-color: #073c6b; margin: 10px; padding: 10px;">
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

        <h1 class="page-title">
            <span>MyLibrary Branch</span>
        </h1>
        <div class="section-divider"></div> <!-- Central border line after title -->
        <div class="container">
            <div class="row library-hours">
                <h2 class="col-12 text-center">Library Hours</h2>
                <div class="col-6 col-sm-4 col-lg-auto custom-spacing"><strong>Monday</strong><br>9am - 8pm</div>
                <div class="col-6 col-sm-4 col-lg-auto custom-spacing"><strong>Tuesday</strong><br>9am - 8pm</div>
                <div class="col-6 col-sm-4 col-lg-auto custom-spacing"><strong>Wednesday</strong><br>9am - 8pm</div>
                <div class="col-6 col-sm-4 col-lg-auto custom-spacing"><strong>Thursday</strong><br>9am - 8pm</div>
                <div class="col-6 col-sm-4 col-lg-auto custom-spacing"><strong>Friday</strong><br>9am - 8pm</div>
                <div class="col-6 col-sm-4 col-lg-auto custom-spacing"><strong>Saturday</strong><br>10am - 4pm</div>
                <div class="col-6 col-sm-4 col-lg-auto custom-spacing"><strong>Sunday</strong><br>Closed</div>
            </div>
        </div>
        
        <div class="container" id="introduction">
            <div class="row">
                <div class="col-md-12">
                    <h1>Welcome to MyLibrary</h1>
                    <p>MyLibrary is an innovative web-based library management system designed to bring the library directly to you, wherever you are. Our platform is more than just a library; it's a comprehensive tool that empowers you to manage your reading and learning experience with ease and convenience.</p>
                </div>
            </div>
        </div>

        
        <div class="container-fluid" id="aboutUs">
            <div class="row">
                <div class="col-md-6">
                    <h1>About Us</h1>
                    <p>At the heart of our community is MyLibrary, the main branch of our library management system. 
                        Located in the center of our digital universe, MyLibrary is not just a place to borrow books—it's a 
                        space to grow, learn, and explore. <br><br>Whether you are looking to dive into your next 
                        great adventure in literature, research for academic or personal projects, or simply explore new learning materials, 
                        MyLibrary is here to support every step of your journey. Join us today and transform the way you read, learn, and 
                        interact with your library.
                    </p>
                </div>
                <div class="col-md-6">
                    <!-- Placeholder for image -->
                    <img src="../images\homeImgs\library-management-system.jpg" alt="librarySystemImg" class="img-fluid">
                </div>
            </div>
        </div>

        <div class="container-fluid" id="ourMission">
            <div class="row">
                <div class="col-md-6">
                    <!-- Image on the left side -->
                    <img src="../images/homeImgs/OurMission.jpg" alt="librarySystemImg" class="img-fluid">
                </div>
                <div class="col-md-6">
                    <!-- Text on the right side -->
                    <h1>Our Mission</h1>
                    <p>At MyLibrary, we are committed to fostering a culture of lifelong learning and enrichment. 
                    Our mission is to provide comprehensive access to resources, support academic and personal growth, and promote a love for reading and discovery.</p>

                    <blockquote class="blockquote">
                        <p class="mb-0">"In the vast ocean of knowledge, every book is a vessel waiting to take us on an extraordinary journey. Let us embrace this adventure with open arms and empower each other to reach new horizons. Together, we can turn the page towards a future rich with discovery and enlightenment."</p>
                        <footer class="blockquote-footer">Bryan Hernandez-Trejo</footer>
                    </blockquote>
                </div>
            </div>
        </div>


       <div class="container-fluid" id="ourHistory">
            <div class="row justify-content-center align-items-center" style="min-height: 20vh;">
                <div class="col-md-8 text-center">
                    <h1>Our History</h1>
                    <p>Founded in 2024, MyLibrary has been a cornerstone of education and enlightenment in our community. We have evolved to meet the changing needs of our 
                    patrons, embracing technological advancements to enhance the library experience.</p>
                </div>
            </div>
        </div>
        <div class="container-fluid" id="managementSystem">
            <div class="row justify-content-center align-items-center" style="min-height: 20vh;">
                <div class="col-md-8 text-center">
                    <h1>About Our Web-Based Library Management System</h1>
                    <p>Driven by a passion for innovation, we are excited to introduce our user-friendly web-based library management system. Designed with the needs of our 
                patrons in mind, our system empowers users to seamlessly access, explore, and manage their reading journey from anywhere, at any time.</p>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="accordion" id="keyfeatures">
                        <h1 class="text-center">Features of MyLibrary:</h1>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                My Bookshelf Page
                            </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample" style="">
                            <div class="accordion-body">
                                Keep track of your currently rented books with our "My Bookshelf" feature. This personalized space allows you to see which books you have borrowed, monitor due dates, and manage renewals without the need to visit the library.
                            </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Availability Checking
                            </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample" style="">
                            <div class="accordion-body">
                                Check the availability of books in real-time, ensuring that you can locate the materials you need with ease.
                            </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Explore Page
                            </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample" style="">
                            <div class="accordion-body">
                                Discover new titles and old favorites with our Explore page. Search for books by title, author, genre, or keywords. Our intuitive search feature makes finding your next read both easy and enjoyable.
                            </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Checkout and Returns
                            </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample" style="">
                            <div class="accordion-body">
                                Streamlined processes for book checkouts and returns, making borrowing and returning materials a breeze.
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>   
        <div class="container map-container">
            <center><h1>All library Locations</h1></center>
            <?php
            $libraries = $library->getLibrariesBlankLatLng();
            // Optionally you can remove this line if you do not want to display raw data on your page
            // print_r($libraries);
            ?>
            <div id="allData" style="display:none;">
                <?php echo json_encode($libraries); ?>
            </div>
            <div id="map"></div>
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
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC134XnlDuhSMGl06diDEuU_3j6jdOzlCg&callback=loadMap">
    </script>
    <!-- link for bootstrap js compatability -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>