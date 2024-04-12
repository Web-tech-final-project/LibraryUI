<!-- php code -->
<?php
session_start();

include("../connection.php");
include("../functions.php");

$user_data = check_login($conn);

$num_books = getNumUserBooks($conn);
$book_data = getBookData($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/about.css">
    <link rel="stylesheet" href="css/pages.css">
 
    <title>Library home</title>
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

        <div class="headerContainer">
            <h1><b>Learn about Our Development Team</b></h1>
        </div>

        <!-- Team Member profiles -->
        <div class="teamMembersTableContainer">

            <div class="row1teamMembers">

                <!-- Anees Alawmleh -->
                <div class="aneesContainer">
                    <h2><b>Anees Alawmleh</b></h2>
                    <img src="/LibraryUI/images/Annes.JPG" width="200px"; height="350px";>
                    <p id="text">I am Anees.</p>
                </div>

                <!-- Mark Eskander -->
                <div class="markContainer">
                    <h2><b>Mark Eskander</b></h2>
                    <img src="/LibraryUI/images/Mark.png" width="290px"; height="250px";>
                    <p id="text">
                    Hey there, I'm Mark, a senior studying Computer Science at MTSU. 
                    I am a back-end developer with MyLibrary and I love it here. 
                    I deal with PHP and MySQL for the most part, ensuring seamless integration across front-end, back-end, and database operations. 
                    Besides the technical stuff, I enjoy spending time with friends and staying active.
                    </p>
                </div>

                <!-- Bryan Hernandez-Trejo -->
                <div class="bryanContainer">
                    <h2><b>Bryan Hernandez-Trejo</b></h2>
                    <img src="/LibraryUI/images/bryan.JPG" width="250px"; height="250px";>
                    <p id="text">Hi, my name is Bryan Hernandez-Trejo and I'm from Nashville, TN. I'm a passionate computer science student 
                        currently enrolled as a Senior at Middle Tennessee State University. I've been working on this team for 3 weeks and it has been an amazing experience. 
                        We have enjoyed working alongside them in the process of web development and learning new technologies. 
                        I have enjoyed being part of this team and helping each other out in this process that we had begun with little knowledge about.
                    </p>
                </div>

            </div>
            
            <div class="row2teamMembers">
                <!-- Uriel Esquivel -->
                <div class="urielContainer">
                    <h2><b>Uriel Esquivel</b></h2>
                </div>

                <!-- Angel Vasquez -->
                <div class="angelContainer">
                    <h2><b>Angel Vasquez</b></h2>
                </div>

                <!-- Brandon Sandoval -->
                <div class="brandonContainer">
                    <h2><b>Brandon Sandoval</b></h2>
                </div>

            </div>
                
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