<!-- php code -->
<?php
session_start();
    include("../connection.php");
    include("../functions.php");

    if ($_SERVER['REQUEST_METHOD'] == "POST")
    {
        // form was posted store data from form
        $userName = $_POST['userName'];
        $password = $_POST['password'];

        // check if empty
        if (!empty($userName) && !empty($password))
        {
            // generate random number for new userId
            $userId = random_num(12);

            // make query and send to DB
            $query = "INSERT INTO users (userId, userName, password)
                      VALUES ('$userId', '$userName', '$password')";
            mysqli_query($conn, $query);
            
            // redirect user to login page
            header("Location: login.php");
            die;
        }
        // invalid info entered
        else 
        {
            echo "Please enter valid credentials.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>MyLibrary: Signup</title>
</head>

<body>
    <div class="container">
        <div style="background-color: darkgray;">
            <form style="width: 50%; margin: auto;" method="POST">
                <h3 style="margin: 10px;">Signup</h3>

                <div class="form-group">
                    <label for="signupUsername">Username</label>
                    <input type="text" class="form-control" id="signupUsername" aria-describedby="emailHelp" placeholder="Enter username" name="userName">
                </div>

                <div class="form-group">
                    <label for="signupPassword">Password</label>
                    <input type="password" class="form-control" id="signupPassword" placeholder="Password" name="password">
                </div>

                <input type="submit" class="btn btn-success" value="Signup">

                <div class="position-absolute top-50 start-50 translate-middle">
                    <p>Already have an account?</p>
                    <a class="btn btn-primary" href="login.php" role="button" style="margin: auto;">Login</a>
                </div>
            </form>
        </div>
    </div>

    <!-- link for bootstrap js compatability -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>