<?php session_start() ?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Sign in</title>
        <link href="custom.css" rel="stylesheet">
    </head>

    <body style="background-color: #f9f9f9">
        <?php
            $username = "";
            $password = "";

            if ( $_SERVER["REQUEST_METHOD"] == "POST" ) { // active when submit
                include "connectToDB.php";
                $username = $_POST["_username"]; // get data
                $password = $_POST["_password"]; // get data

                // check by username
                $sql = "select * from account where username = '" . $username . "' and password = '" . $password . "'";
                $query = mysqli_query( $con , $sql ); // check if user exists
                $result = $query->num_rows;

                // check by eamil address
                $sql1 = "select * from account where email = '" . $username . "' and password = '" . $password . "'";
                $query = mysqli_query( $con , $sql ); // check if user exists
                $result1 = $query->num_rows;

                if ( $result == 1 || $result == 1 ) $message = "yes";
                else $message = "no";

                include "disconnectToDB.php";
            }
         ?>

        <!---------------------------------------------------------------------------------->
        <h2 style="text-align: center;">Sign in to <span style="color: red">message-board</span></h2>

        <div class="login"> <!--login textbox-->
            <form action="<?php echo htmlspecialchars( $_SERVER["PHP_SELF"] ); ?>" method="post">
                Username or email address<br>
                <input type="text" name="_username" size=15 value="<?php echo $username ?>"><br>

                Password<br>
                <input type="password" name="_password" size=15><br>
                <input type="submit" value="Sign in">
            </form>
        </div>
    </body>
</html>
