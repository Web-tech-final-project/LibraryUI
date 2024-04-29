<!-- php code -->
<?php
session_start();

    include("../connection.php");
    include("../functions.php");

    $user_data = check_login($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/pages.css">
    <title>MyLibrary home</title>
</head>

<body>
    <div class="container-fluid">
        <!-- Logo -->
        <div class="text-center">
            <img src="../images/myLibraryLogo.JPG" alt="MyLibrary logo" >
        </div>
        <!-- navbar -->
        <ul class="nav justify-content-center" style="background-color: #073c6b; margin: 10px; padding: 10px;">
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
        <h1 class="page-title">
            <span>MyLibrary Branch</span>
        </h1>
        <div class="office-hours--wrapper">
            <div>
                <div>
                    <div class="office-hours office-hours-status--open">
                    <div class="office-hours__item">
                            <span class="office-hours__item-label" style="width: 5.4em;">Sunday</span>
                                  <span class="office-hours__item-slots">2:00 pm-5:00 pm</span>
                                <span><br></span>
                    </div>
                    <div class="office-hours__item">
                            <span class="office-hours__item-label" style="width: 5.4em;">Monday</span>
                                  <span class="office-hours__item-slots">10:00 am-8:00 pm</span>
                                <span><br></span>
                    </div>
                    <div class="office-hours__item">
                            <span class="office-hours__item-label" style="width: 5.4em;">Tuesday</span>
                                  <span class="office-hours__item-slots">10:00 am-8:00 pm</span>
                                <span><br></span>
                    </div>
                    <div class="office-hours__item">
                            <span class="office-hours__item-label" style="width: 5.4em;">Wednesday</span>
                                  <span class="office-hours__item-slots">10:00 am-8:00 pm</span>
                                <span><br></span>
                    </div>
                    <div class="office-hours__item">
                            <span class="office-hours__item-label" style="width: 5.4em;">Thursday</span>
                                  <span class="office-hours__item-slots">10:00 am-8:00 pm</span>
                                <span><br></span>
                    </div>
                    <div class="office-hours__item">
                            <span class="office-hours__item-label" style="width: 5.4em;">Friday</span>
                                  <span class="office-hours__item-slots">10:00 am-6:00 pm</span>
                                <span><br></span>
                    </div>
                    <div class="office-hours__item">
                            <span class="office-hours__item-label" style="width: 5.4em;">Saturday</span>
                                  <span class="office-hours__item-slots">10:00 am-5:00 pm</span>
                                <span><br></span>
                    </div>
                </div>
              </div>
            </div>      
        </div>
        <div class="container-fluid" id="aboutUs">
            <h1> About Us </h1>
            <p> Welcome to MyLibrary, your gateway to a world of knowledge and exploration. Established with a vision to empower 
                and inspire our community through access to information, we are proud to introduce our innovative web-based library management system.
            </p>
        </div>
        <div class="container-fluid" id="ourMission">
            <h1> Our Mission </h1>
            <p> At MyLibrary, we are committed to fostering a culture of lifelong learning and enrichment. Our mission is to provide comprehensive 
                access to resources, support academic and personal growth, and promote a love for reading and discovery.
            </p>
        </div>
        <div class="container-fluid" id="ourHistory">
            <h1> Our History </h1>
            <p> Founded in 2024, MyLibrary has been a cornerstone of education and enlightenment in our community. We have evolved to meet the changing needs of our 
                patrons, embracing technological advancements to enhance the library experience.
            </p>
        </div>
        <div class="container-fluid" id="managementSystem">
            <h1> About Our Web-Based Library Management System </h1>
            <p> Driven by a passion for innovation, we are excited to introduce our user-friendly web-based library management system. Designed with the needs of our 
                patrons in mind, our system empowers users to seamlessly access, explore, and manage their reading journey from anywhere, at any time.
            </p>
        </div>
        <div class="container-fluid" id="keyfeatures">
            <h2> Key features of our system include: </h1>
            <p> Driven by a passion for innovation, we are excited to introduce our user-friendly web-based library management system. Designed with the needs of our 
                patrons in mind, our system empowers users to seamlessly access, explore, and manage their reading journey from anywhere, at any time.
            </p>
       
            <ul class="features justify-content-center">
            <li>Book Information: Easily search for detailed information about books in our collection, including summaries, author bios, and reviews.</li>
            <li>Availability Checking: Check the availability of books in real-time, ensuring that you can locate the materials you need with ease.</li>
            <li>Reading Lists Management: Create personalized reading lists, categorizing books as 'Read' or 'To Read' for easy organization and tracking.</li>
            <li>Checkout and Returns: Streamlined processes for book checkouts and returns, making borrowing and returning materials a breeze.</li>
            </ul>
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
                        <a href="" class="me-4 text-reset">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="" class="me-4 text-reset">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                        <a href="" class="me-4 text-reset">
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
                                <a href="#!" class="text-reset">FAQ</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">Help</a>
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