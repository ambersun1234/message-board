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

        <?php // prevent user from direct access from url , redirect to signIn.php
            if ( $_SESSION['loggedin'] == false ) header("Location: signIn.php");
         ?>

        <?php
            include "connectToDB.php";

            $userid = 0;
            $dbUsername = $dbEmail = $dbPassword = "";
            $username = $email = $oldPassword = $newPassword = $cnewPassword = $imageFilePath = "";
            $usernameErr = $emailErr = $oldPasswordErr = $newPasswordErr = $cnewPasswordErr = $fileErr = "";
            $usernameSuc = $emailSuc = $passwordSuc = $fileSuc = "";
            $usernameFail = $emailFail = $passwordFail = "";
            $postNumber = $commandNumber = 0;

            getData( $con );

            if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset( $_POST["submit"] ) ) { // active when submit
                writeData();
                checkUsername( $username , $dbUsername , $con );
                checkEmail( $email , $dbEmail , $con );
                checkPassword( $oldPassword , $newPassword , $cnewPassword , $dbPassword , $con );
                if ( file_exists( $_FILES["fileToUpload"]["tmp_name"] ) || is_uploaded_file( $_FILES["fileToUpload"]["tmp_name"] ) ) {
                    // enter this block when file is uploaded
                    uploadImage( $con );
                }
                getData( $con );
            }

            function getData( $con ) {
                $sql = "select * from account where username = '" . $_SESSION['user'] . "'";
                $query = mysqli_query( $con , $sql );
                $row = $query->fetch_assoc();

                $GLOBALS["imageFilePath"] = $row["image"];
                $GLOBALS["userid"] = $row["userid"];
                $GLOBALS["dbUsername"] = $row["username"];
                $GLOBALS["dbEmail"] = $row["email"];
                $GLOBALS["dbPassword"] = $row["password"];
                if ( $GLOBALS["imageFilePath"] == "" ) $GLOBALS["imageFilePath"] = "default.jpeg"; // if personal image not specify , set to default
                //---------------------------------------------------------------------------------
                $GLOBALS["username"] = $row["username"];
                $GLOBALS["email"] = $row["email"];
                //---------------------------------------------------------------------------------
                $sql = "select count(*) as total from post where userid = '" . $GLOBALS["userid"] . "'";
                $query = mysqli_query( $con , $sql );
                $row = $query->fetch_assoc();
                $GLOBALS["postNumber"] = $row["total"];
                //---------------------------------------------------------------------------------
                $sql = "select count(*) as total from command where userid = '" . $GLOBALS["userid"] . "'";
                $query = mysqli_query( $con , $sql );
                $row = $query->fetch_assoc();
                $GLOBALS["commandNumber"] = $row["total"];
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
                    else $GLOBALS["usernameFail"] = "Update failed , please try again.<br>";
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
                    else $GLOBALS["emailFail"] = "Update failed , please try again.<br>";
                }
            }
            function checkPassword( $oldPassword , $newPassword , $cnewPassword , $dbPassword , $con ) {
                if ( $newPassword != "" ) {
                    // check old password correct or not
                    if ( !password_verify( $oldPassword , $dbPassword ) ) { // use php function to check password correct or not
                        $GLOBALS["oldPasswordErr"] = "Incorrect password!!<br>";
                    }
                    // check new password valid or not
                    if ( strlen( $newPassword ) <= 7 ) { // check at least 8 characters
                        $GLOBALS["newPasswordErr"] = "Must at least 8 characters!!<br>";
                    }
                    if ( ( !preg_match( '/[A-Z]/' , $newPassword ) || !preg_match( '/[a-z]/' , $newPassword ) ) && !preg_match( '/[0-9]/', $newPassword ) ) {
                        $GLOBALS["newPasswordErr"] = "Invalid password!!<br>";
                    }
                    // confirm new password
                    if ( $newPassword != $cnewPassword ) {
                        $GLOBALS["cnewPasswordErr"] = "Incorrect confirm password!!<br>";
                    }
                    // update to database if no error
                    if ( $GLOBALS["oldPasswordErr"] == "" && $GLOBALS["newPasswordErr"] == "" && $GLOBALS["cnewPasswordErr"] == "" ) {
                        $newPassword = password_hash( $newPassword , PASSWORD_DEFAULT ); // encrypt
                        $sql = "update account set password = '" . $newPassword . "' where userid = '" . $GLOBALS["userid"] . "'";
                        $query = mysqli_query( $con , $sql );
                        if ( $query ) $GLOBALS["passwordSuc"] = "Update successfully!!<br>";
                        else $GLOBALS["passwordFail"] = "Update failed , please try again.<br>";
                    }
                }
            }
            function uploadImage( $con ) {
                if ( isset( $_FILES["fileToUpload"] ) ) { // go into block only if choose file to upload
                    $targetDir = "images/";
                    $fileType = basename( $_FILES["fileToUpload"]["type"] );
                    $targetFile = $targetDir . $GLOBALS["userid"] . "_image." . $fileType; // rename file
                    $imagePath = $GLOBALS["userid"] . "_image." . $fileType;
                    if ( !checkFileType( $fileType ) == true ) { // check file type
                        $GLOBALS["fileErr"] = "Only JPG , JPEG and Png are allowed!!<br>";
                        return;
                    }
                    if ( move_uploaded_file( $_FILES["fileToUpload"]["tmp_name"] , $targetFile ) ) { // move image to images/ directory
                        $sql = "update account set image = '" . $imagePath . "' where userid = '" . $GLOBALS["userid"] . "'";
                        $query = mysqli_query( $con , $sql );
                        if ( $query ) $GLOBALS["fileSuc"] = "Update successfully!!<br>";
                        else $GLOBALS["fileErr"] = "Update failed , please try again.<br>";
                    }
                    else {
                        $GLOBALS["fileErr"] = "Update failed , please try again.<br>";
                    }
                    return;
                }
            }
            function checkFileType( $type ) {
                if ( $type == "jpg" || $type == "jpeg" || $type == "png" ) return true;
                else return false;
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
                <form method="post" action="<?php echo htmlspecialchars( $_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
                     <div class="col-xs-3" style="padding: 5px 5px;">
                         <h3 style="text-align: center;">Profile Picture</h3>
                         <?php echo "<img src='/images/" . $imageFilePath . "'>"?>
                         <input accept="image/jpeg,image/png,image/jpg" type="file" name="fileToUpload">
                         <?php
                            if ( $fileErr != "" ) echo "<div class='changeInvalid' style='text-align: center;'>" . $fileErr . "</div>";
                            else if ( $fileSuc != "" ) echo "<div class='changeValid' style='text-align: center;'>" . $fileSuc . "</div>";
                          ?>
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
                         <?php echo "<div class='changeInvalid'>" . $oldPasswordErr . "</div>"; ?>

                         New password:
                         <input type="password" placeholder="your new password" name="_newPassword"><br>
                         <div class="passwordWarn">use at least one letter , one numeral and six characters</div>
                         <?php echo "<div class='changeInvalid'>" . $newPasswordErr . "</div>"; ?>

                         Confirm new password:
                         <input type="password" placeholder="confirm your new password" name="_cnewPassword"><br>
                         <?php echo "<div class='changeInvalid'>" . $cnewPasswordErr . "</div>"; ?>
                         <?php echo "<div class='changeValid'>" . $passwordSuc . "</div>"; ?>


                         <button class="btn bth-default update_button" type="submit" name="submit">Update</button>
                     </div>
                </form>
            </div>
         </div>
    </body>
</html>
