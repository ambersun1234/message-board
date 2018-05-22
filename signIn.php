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
            $username = $password = "";

            if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset( $_POST["SUBMIT"] ) ) { // active when submit

                include "connectToDB.php";

                $username = $_POST["_username"]; // get data
                $password = $_POST["_password"]; // get data

                $sql = "select * from account where username = '" . $username . "'";
                $query = mysqli_query( $con , $sql ); // check if user exists
                $row = $query->fetch_assoc();

                checkUsername( $username , $row["username"] );
                checkPassword( $password , $row["password"] );

                if ( checkValid() ) {
                    $_SESSION["loggedin"] = true;
                    $_SESSION["user"] = $username;
                    header("Location: index.php"); //redirct to index.php
                }

                include "disconnectToDB.php";
            }
            function checkUsername( $username , $dbUsername ) {
                if ( $username == "" ) $GLOBALS["usernameErr"] = "Username can't be blank!!<br>";
                else if ( $username != $dbUsername ) $GLOBALS["usernameErr"] = "User not found!!<br>";
            }
            function checkPassword( $password , $password_hash ) { // use php function to check if password is correct or not
                if ( $password == "" ) $GLOBALS["passwordErr"] = "Password can't be blank!!<br>";
                if ( !password_verify( $password , $password_hash ) ) $GLOBALS["passwordErr"] = "Incorrect password!!<br>";
            }
            function checkValid() {
                if ( $GLOBALS["usernameErr"] =="" && $GLOBALS["passwordErr"] == "" ) return true;
                else return false;
            }
         ?>

        <!---------------------------------------------------------------------------------->
        <h2 style="text-align: center;">Sign in to <span style="color: #f74c3c">message-board</span></h2>

        <div class="login">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <span>Username</span><br>
                <input type="text" name="_username" value="<?php echo $username ?>"><br>
                <?php if ( isset( $usernameErr ) ) echo "<div class='invalid'>" . $usernameErr . "</div>"; ?>

                <span >Password</span><br>
                <input type="password" name="_password"><br>
                <?php if ( isset( $passwordErr ) ) echo "<div class='invalid'>" . $passwordErr . "</div>"; ?>

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
