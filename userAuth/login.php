<!-- php code -->
<?php
session_start();
include("../connection.php");
include("../functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // form was posted store data from form
    $userName = $_POST['userName'];
    $password = $_POST['password'];

    // check if empty
    if (!empty($userName) && !empty($password)) {
        // make query and send to DB
        $query = "SELECT * FROM users
                      WHERE userName = '$userName' and password = '$password'";
        $result = mysqli_query($conn, $query);

        // validate result
        if ($result) {
            if ($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);

                // redirect user to welcome page
                $_SESSION['id'] = $user_data['id'];
                header("Location: ../libraryService/libraryHome.php");
                die;
            }
        }
        echo "Wrong Username or Password.";
    }
    // invalid info entered
    else {
        echo "Please enter valid values.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="userAuth.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <title>MyLibrary: Login</title>

    
</head>

<body>
    <div class="container">
        <div class="login-form">
            <form method="post">
                <h3 style="text-align: center;">MyLibrary Login</h3>

                <div class="form-group">
                    <label for="loginUsername">Username</label>
                    <input type="text" class="form-control" id="loginUsername" aria-describedby="emailHelp" placeholder="Enter username" name="userName">
                </div>

                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <input type="password" class="form-control" id="loginPassword" placeholder="Password" name="password">
                </div>

                <button type="submit" class="btn btn-success" name="Login">Login</button>

                <div class="signup-section">
                    <p>Don't have an account? <a href="signup.php">Signup</a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- link for bootstrap js compatability -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>