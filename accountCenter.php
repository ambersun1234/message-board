<?php session_start(); ?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Account Center</title>

        <!-- custom.css -->
        <link href="custom.css" rel="stylesheet" type="text/css">

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body style="background-color: #f9f9f9">
        <?php include "statusColumn.php"; ?>

        <?php
            include "connectToDB.php";

            $userid = 0;
            $dbUsername = $dbEmail = $dbPassword = "";
            $username = $email = $oldPassword = $newPassword = $cnewPassword = "";
            $usernameErr = $emailErr = $oldPasswordErr = $newPasswordErr = $cnewPasswordErr = "";
            $usernameSuc = $emailSuc = $passwordSuc = "";
            $postNumber = $commandNumber = 0;

            // get data
            $sql = "select * from account where username = '" . $_SESSION['user'] . "'";
            $query = mysqli_query( $con , $sql );
            $row = $query->fetch_assoc();

            $imageFilePath = $row["image"];
            $userid = $row["userid"];
            $dbUsername = $row["username"];
            $dbEmail = $row["email"];
            $dbPassword = $row["password"];
            if ( $imageFilePath == "" ) $imageFilePath = "default.jpeg"; // if personal image not specify , set to default
            //---------------------------------------------------------------------------------
            $username = $row["username"];
            $email = $row["email"];
            //---------------------------------------------------------------------------------
            $sql = "select count(*) as total from post where userid = '" . $userid . "'";
            $query = mysqli_query( $con , $sql );
            $row = $query->fetch_assoc();
            $postNumber = $row["total"];
            //---------------------------------------------------------------------------------
            $sql = "select count(*) as total from command where userid = '" . $userid . "'";
            $query = mysqli_query( $con , $sql );
            $row = $query->fetch_assoc();
            $commandNumber = $row["total"];
            // end get data
            if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset( $_POST["submit"] ) ) { // active when submit
                writeData();
                checkUsername( $username , $dbUsername , $con );
                checkEmail( $email , $dbEmail , $con );
            }
            function checkUsername( $username , $dbUsername , $con ) {
                if ( $username == $dbUsername || $username == "" ) {
                    $GLOBALS["username"] = $dbUsername;
                    return;
                }
                $sql = "select username from account where username = '" . $username . "'";
                $query = mysqli_query( $con , $sql );
                if ( $query->num_rows != 0 ) $GLOBALS["usernameErr"] = "Duplicate username!!<br>";
                else { // update database
                    $sql = "update account set username = '" . $username . "' where userid = '" . $GLOBALS["userid"] . "'";
                    $query = mysqli_query( $con , $sql );
                    unset( $_SESSION['user'] ); // delete previous session
                    $_SESSION['user'] = $username; // add new session
                    if ( $query ) $GLOBALS["usernameSuc"] = "Update successfully!!<br>";
                }
            }
            function checkEmail( $email , $dbEmail , $con ) {
                if ( $email == $dbEmail || $email == "" ) {
                    $GLOBALS["email"] = $dbEmail;
                    return;
                }
                $sql = "select email from account where email = '" . $email . "'";
                $query = mysqli_query( $con , $sql );
                if ( $query->num_rows != 0 ) $GLOBALS["emailErr"] = "Duplicate email!!<br>";
                else if ( !filter_var( $email , FILTER_VALIDATE_EMAIL ) ) $GLOBALS["emailErr"] = "Invalid email format!!<br>";
                else { // update database
                    $sql = "update account set email = '" . $email . "' where userid = '" . $GLOBALS["userid"] . "'";
                    $query = mysqli_query( $con , $sql );
                    if ( $query ) $GLOBALS["emailSuc"] = "Update successfully!!<br>";
                }
            }
            function writeData() { // write into php variables
                $GLOBALS["username"] = $_POST["_username"];
                $GLOBALS["email"] = $_POST["_email"];
                $GLOBALS["oldPassword"] = $_POST["_oldPassword"];
                $GLOBALS["newPassword"] = $_POST["_newPassword"];
                $GLOBALS["cnewPassword"] = $_POST["_cnewPassword"];
            }
            include "disconnectToDB.php";
         ?>

         <div class="accountCenter">
             <div class="container">
                <form method="post" action="<?php echo htmlspecialchars( $_SERVER["PHP_SELF"]);?>">
                     <div class="col-xs-3" style="padding: 5px 5px;">
                         <h3 style="text-align: center;">Profile Picture</h3>
                         <?php echo "<img src='/images/" . $imageFilePath . "'>"?>
                         <input accept="image/jpeg" type="file" name="fileToUpload"><br>
                     </div>
                     <div class="col-xs-6" style="padding: 5px 5px;">
                         <h3>Profile</h3>
                         <hr>
                         Username:<br>
                         <input type="text" name="_username" placeholder="Pick a username" value="<?php echo $username; ?>"><br>
                         <?php
                            if ( $usernameErr != "" ) echo "<div class='changeInvalid'>" . $usernameErr . "</div>";
                            else if ( $usernameSuc != "" ) echo "<div class='changeValid'>" . $usernameSuc . "</div>";
                          ?>

                         Email:<br>
                         <input type="text" name="_email" placeholder="you@gmail.com" value="<?php echo $email; ?>"><br>
                         <?php
                            if ( $emailErr != "" ) echo "<div class='changeInvalid'>" . $emailErr . "</div>";
                            else if ( $emailSuc != "" ) echo "<div class='changeValid'>" . $emailSuc . "</div>";
                          ?>

                         <?php echo "Total post number = " . $postNumber . "<br>" ?>
                         <?php echo "Total command number = " . $commandNumber . "<br>" ?>

                         <h3>Change Password</h3>
                         <hr>
                         Old password:
                         <input type="password" placeholder="your old password" name="_oldPassword"><br>
                         <?php echo "<div class='signUPvalid'>" . $oldPasswordErr . "</div>"; ?>

                         New password:
                         <input type="password" placeholder="your new password" name="_newPassword"><br>
                         <?php echo "<div class='signUPvalid'>" . $newPasswordErr . "</div>"; ?>

                         Confirm new password:
                         <input type="password" placeholder="confirm your new password" name="_cnewPassword"><br>
                         <?php echo "<div class='signUPvalid'>" . $cnewPasswordErr . "</div>"; ?>


                         <button class="btn bth-default update_button" type="submit" name="submit">Update</button>
                     </div>
                </form>
            </div>
         </div>
    </body>
</html>
