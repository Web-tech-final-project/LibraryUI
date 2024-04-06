<!-- php code -->
<?php
session_start();

    include("connection.php");
    include("functions.php");

    $user_data = check_login($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>MyLibrary: Welcome</title>
</head>

<body>
    <div class="container">
        <h1 style="text-align: center; padding: 50px;">
            <?php echo $user_data['userName']?>, welcome to MyLibrary! <br>
            Please get started by using the button below.
        </h1>

        <!-- take user to desired page (continue/logout) -->
        <div class="position-absolute top-50 start-50 translate-middle">
            <a class="btn btn-primary" href="libraryService/libraryHome.php" role="button" style="margin: auto;">Continue</a>
            <a class="btn btn-danger" href="userAuth/logout.php" role="button" style="margin: auto;">Logout</a>
        </div>
    </div>

    <!-- link for bootstrap js compatability -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>