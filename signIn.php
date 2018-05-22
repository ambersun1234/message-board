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

                // echo "hash password = " . password_hash( $password , PASSWORD_DEFAULT ) . "<br>";

                // if ( password_verify( $password , password_hash( $password , PASSWORD_DEFAULT ) ) ) echo "true<br>";
                // else echo "false<br>";

                $sql = "select * from account where username = '" . $username . "'";
                $query = mysqli_query( $con , $sql ); // check if user exists
                $row = $query->fetch_assoc();
                $result = $query->num_rows;

                if ( $result == 1 && checkPassword( $password , $row["password"] ) ) { // mark that the user logged in
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user'] = $username;
                    header("Location: index.php"); // redirect to index.php
                }
                else $loginError = "Incorrect username or password.";

                include "disconnectToDB.php";
            }
            function checkPassword( $password , $password_hash ) { // use php function to check if password is correct or not
                if ( password_verify( $password , $password_hash ) ) return true;
                else return false;
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
                <span>Username</span><br>
                <input type="text" name="_username" value="<?php echo $username ?>"><br>

                <span >Password</span><br>
                <input type="password" name="_password"><br>
                <button type="submit" class="btn btn-default" name="SUBMIT">Sign in</button>
            </form>
        </div>
        <br>
        <div class="login_new_to">
            <div class="login_new_to_message">
                New to message-board? <br><a href="/index.php#signUp">Create an account</a>
            </div>
        </div>
    </body>
</html>
