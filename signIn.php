<?php session_start() ?> <!--session start , always on the first line of the code-->

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Sign in</title>
        <link href="custom.css" rel="stylesheet" type="text/css">
    </head>

    <body style="background-color: #f9f9f9">
        <?php
            $username = "";
            $password = "";
            $loginError = "";

            if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset( $_POST["SUBMIT"] ) ) { // active when submit

                include "connectToDB.php";

                $username = $_POST["_username"]; // get data
                $password = $_POST["_password"]; // get data

                $sql = "select * from account where username = '" . $username . "' and password = '" . $password . "'";
                $query = mysqli_query( $con , $sql ); // check if user exists
                $result = $query->num_rows;

                if ( $result == 1 ) { // mark that the user logged in
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user'] = $username;
                    header("Location: index.php"); // redirt to index.php
                }
                else $loginError = "Incorrect username or password.";

                include "disconnectToDB.php";
            }
         ?>

        <!---------------------------------------------------------------------------------->
        <h2 style="text-align: center;">Sign in to <span style="color: #f74c3c">message-board</span></h2>

        <div class="login_error">
            <?php
                if ( isset( $loginError ) ) echo $loginError;
             ?>
        </div>
        <br>

        <div class="login">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <span style="font-weight: bold">Username</span><br>
                <input type="text" name="_username" size=15 value="<?php echo $username ?>"><br>

                <span style="font-weight: bold">Password</span><br>
                <input type="password" name="_password" size=15><br>
                <input type="submit" name="SUBMIT" value="Sign in">
            </form>
        </div>
        <br>
        <div class="login_new_to">
            <div class="login_new_to_message">
                New to message-board? <a href="signUp.php">Create an account</a>
            </div>
        </div>
    </body>
</html>
