<?php session_start(); ?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Account Center</title>

        <!-- custom.css -->
        <link href="custom.css" rel="stylesheet" type="text/css">

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    </head>

    <body style="background-color: #f9f9f9">
        <?php include "statusColumn.php"; ?>

        <?php // prevent user from direct access from url , redirect to signIn.php
            if ( $_SESSION['loggedin'] == false ) header("Location: signIn.php");
         ?>


        <?php // delete account
           if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset( $_POST["submitDelete"] ) ) { // active when submit
              include "connectToDB.php";

              $usernameOrEmailD = $usernameOrEmailDErr = "";
              $deleteMyAccountD = $deleteMyAccountDErr = "";
              $passwordD = $passwordDErr = "";

              $usernameOrEmailD = getDataD( $con , $_POST["_usernameOrEmail"] );
              $deleteMyAccountD  = getDataD( $con , $_POST["_deleteMyAccount"] );
              $passwordD = getDataD( $con , $_POST["_password"] );

              $sql = "select * from account where ( username = '" . $usernameOrEmailD . "' or email = '" . $usernameOrEmailD . "' ) and username = '" . $_SESSION['user'] . "'";
              $query = mysqli_query( $con , $sql );
              $row = $query->fetch_assoc();
              $id = $row["userid"];
              $result = $query->num_rows;

              checkUsernameOrEmailD( $result );
              checkPatternD( $deleteMyAccountD , "delete my account" );
              checkPasswordD( $passwordD , $row["password"] );

              if ( checkValidD() ) deleteAccountD( $con , $id );

              include "disconnectToDB.php";
          }
        //-------------------------------------------------------------------------------------------------------------------
          function checkValidD() {
              if ( $GLOBALS["usernameOrEmailDErr"] != "" || $GLOBALS["deleteMyAccountDErr"] != "" || $GLOBALS["passwordDErr"] != "" || $GLOBALS["deleteD"] ) return false;
              else return true;
          }
          function checkUsernameOrEmailD( $result ) {
              if ( $result < 1 ) {// username or email not found in database
                  $GLOBALS["deleteD"] = "Delete failed!!<br>"; // set error message
                  $GLOBALS["usernameOrEmailDErr"] = "User or email not found!!<br>";
              }
              else $GLOBALS["usernameOrEmailDErr"] = $GLOBALS["deleteD"] = "";
          }
          function checkPatternD( $deleteMyAccount , $pattern ) {
              if ( $deleteMyAccount != $pattern ) {
                  $GLOBALS["deleteD"] = "Delete failed!!<br>";
                  $GLOBALS["deleteMyAccountDErr"] = "Pattern not matched!!<br>";
              }
              else $GLOBALS["deleteMyAccountDErr"] = $GLOBALS["deleteD"] = "";
          }
          function checkPasswordD( $password , $dbPassword ) {
              if ( !password_verify( $password , $dbPassword ) ) {
                  $GLOBALS["deleteD"] = "Delete failed!!<br>";
                  $GLOBALS["passwordDErr"] = "Incorrect confirm password!!<br>";
              }
              else $GLOBALS["passwordDErr"] = $GLOBALS["deleteD"] = "";
          }
          function deleteAccountD( $con , $id ) {
              $sql = "delete from comment where userid = '" . $id . "'"; // delete comment sql
              $query = mysqli_query( $con , $sql );
              if ( $query ) { // delete comment success
                  $sql = "delete from post where userid = '" . $id . "'";
                  $query = mysqli_query( $con , $sql );
                  if ( $query ) { // delete post success
                      $sql = "delete from account where userid = '" . $id . "'";
                      $query = mysqli_query( $con , $sql );
                      if ( !$query ) $GLOBALS["deleteD"] = "Something went wrong , please submit again...3<br>";
                  }
                  else $GLOBALS["deleteD"] = "Something went wrong , please submit again...2<br>";
              }
              else $GLOBALS["deleteD"] = "Something went wrong , please submit again...1<br>";

              // check $GLOBALS["deleteD"] status , decide redirection
              if ( $GLOBALS["deleteD"] == "" ) {
                  session_unset(); // clear all session
                  header("Location: index.php");
              }
          }

          function getDataD( $con , $data ) { // prevent xss and sql injection
              $data = stripslashes( $data ); // remove all \
              $data = htmlspecialchars( $data ); // turn &"'<> to real entity
              $data = mysqli_real_escape_string( $con , $data );
              return $data;
          }
        ?>
<!-------------------------------------------------------------------------------------------------------------------------------------------------->
        <?php // update account
            $userid = 0;
            $dbUsername = $dbEmail = $dbPassword = "";
            $username = $email = $oldPassword = $newPassword = $cnewPassword = $imageFilePath = "";
            $usernameErr = $emailErr = $oldPasswordErr = $newPasswordErr = $cnewPasswordErr = $fileErr = "";
            $usernameSuc = $emailSuc = $passwordSuc = $fileSuc = "";
            $usernameFail = $emailFail = $passwordFail = "";
            $postNumber = $commentNumber = 0;

            include "connectToDB.php";
            getData( $con );

            if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset( $_POST["submit"] ) ) { // active when submit
                writeData( $con );
                checkUsername( $username , $dbUsername , $con );
                checkEmail( $email , $dbEmail , $con );
                checkPassword( $oldPassword , $newPassword , $cnewPassword , $dbPassword , $con );
                if ( file_exists( $_FILES["fileToUpload"]["tmp_name"] ) || is_uploaded_file( $_FILES["fileToUpload"]["tmp_name"] ) ) {
                    // enter this block when file is uploaded
                    uploadImage( $con );
                }
                getData( $con );
            }
            include "disconnectToDB.php";

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
                $sql = "select count(*) as total from comment as c where c.userid = " . $GLOBALS["userid"];

                $query = mysqli_query( $con , $sql );
                $row = $query->fetch_assoc();
                $GLOBALS["commentNumber"] = $row["total"];
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
                        if ( $query ) {
                            $_SESSION["image"] = $imagePath;
                            $GLOBALS["fileSuc"] = "Update successfully!!<br>";
                        }
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
            function writeData( $con ) { // write into php variables
                $GLOBALS["username"] = getData2( $con , $_POST["_username"] );
                $GLOBALS["email"] = getData2( $con , $_POST["_email"] );
                $GLOBALS["oldPassword"] = getData2( $con , $_POST["_oldPassword"] );
                $GLOBALS["newPassword"] = getData2( $con , $_POST["_newPassword"] );
                $GLOBALS["cnewPassword"] = getData2( $con , $_POST["_cnewPassword"] );
            }
            function getData2( $con , $data ) { // prevent xss and sql injection
                $data = stripslashes( $data ); // remove all \
                $data = htmlspecialchars( $data ); // turn &"'<> to real entity
                $data = mysqli_real_escape_string( $con , $data );
                return $data;
            }
         ?>
         <div class="accountCenter">
             <div class="container">
                <form class="myform" method="post" action="<?php echo htmlspecialchars( $_SERVER["PHP_SELF"] );?>" enctype="multipart/form-data">
                     <div class="col-xs-3" style="padding: 5px 5px;">
                         <h3 style="text-align: center;">Profile Picture</h3>
                         <?php echo "<img src='/images/" . $imageFilePath . "'>"?>
                         <input accept="image/jpeg,image/png,image/jpg" type="file" name="fileToUpload">
                         <?php
                            if ( $fileErr != "" ) echo "<div class='invalid' style='text-align: center;'>" . $fileErr . "</div>";
                            else if ( $fileSuc != "" ) echo "<div class='valid' style='text-align: center;'>" . $fileSuc . "</div>";
                          ?>
                     </div>

                     <div class="col-xs-8" style="padding: 5px 5px;">
                         <h3>Profile</h3>
                         <hr>
                         Username:<br>
                         <input type="text" name="_username" placeholder="Pick a username" value="<?php echo $username; ?>"><br>
                         <?php
                            if ( $usernameErr != "" ) echo "<div class='invalid'>" . $usernameErr . "</div>";
                            else if ( $usernameSuc != "" ) echo "<div class='valid'>" . $usernameSuc . "</div>";
                          ?>

                         Email:<br>
                         <input type="text" name="_email" placeholder="you@gmail.com" value="<?php echo $email; ?>"><br>
                         <?php
                            if ( $emailErr != "" ) echo "<div class='invalid'>" . $emailErr . "</div>";
                            else if ( $emailSuc != "" ) echo "<div class='valid'>" . $emailSuc . "</div>";
                          ?>

                         <?php
                            echo "Total post number = " . $postNumber . " ";
                            if ( $postNumber > 0 ) echo '<a href="view.php?id=' . $userid . '&which=post&sort=apt">view post</a><br>';
                           else echo "<br>";
                          ?>
                         <?php
                            echo "Total comment number = " . $commentNumber . " ";
                            if ( $commentNumber > 0 ) echo '<a href="view.php?id=' . $userid . '&which=comment&sort=apt">view comment</a><br>';
                            else echo "<br>";
                          ?>

                         <h3>Change Password</h3>
                         <hr>
                         Old password:
                         <input type="password" placeholder="your old password" name="_oldPassword"><br>
                         <?php echo "<div class='invalid'>" . $oldPasswordErr . "</div>"; ?>

                         New password:
                         <input type="password" placeholder="your new password" name="_newPassword"><br>
                         <div class="passwordWarn">use at least one letter , one numeral and six characters</div>
                         <?php echo "<div class='invalid'>" . $newPasswordErr . "</div>"; ?>

                         Confirm new password:
                         <input type="password" placeholder="confirm your new password" name="_cnewPassword"><br>
                         <?php echo "<div class='invalid'>" . $cnewPasswordErr . "</div>"; ?>
                         <?php echo "<div class='valid'>" . $passwordSuc . "</div>"; ?>
                         <br>
                         <button class="btn bth-default update_button" type="submit" name="submit">Update</button>

                    </form>
<!---------------------------------------------------------------------------------------------------------->
                         <h3 class="deletAccount">Delete your account</h3>
                         <hr>
                         Once you delete your account, there is no going back. Please be certain.<br><br>

                         <div class="deleteAccountWarning">
                             <i class="fa fa-exclamation-triangle">This is extremely important.</i><br><br>
                             We will <b>immediately delete all of your post and comment message</b><br><br>
                             You will no longer be billed , and your username will be available to anyone on Message Board.<br>
                         </div>
                         <br>

                         <form method="post" action="<?php echo htmlspecialchars( $_SERVER["PHP_SELF"] );?>" enctype="multipart/form-data">
                             <b>Your Username or email:</b><br>
                             <input type="text" placeholder="Your username or email" name="_usernameOrEmail" value="<?php echo $usernameOrEmailD; ?>"><br>
                             <?php if ( isset( $usernameOrEmailDErr ) ) echo "<div class='invalid'>" . $usernameOrEmailDErr . "</div>"; ?>

                             <b>To verify , type</b> delete my account <b>below</b>.
                             <input type="text" placeholder="delete my account" name="_deleteMyAccount" value="<?php echo $deleteMyAccountD; ?>"><br>
                             <?php if ( isset( $deleteMyAccountDErr ) ) echo "<div class='invalid'>" . $deleteMyAccountDErr . "</div>"; ?>

                             <b>Comfirm your password</b>
                             <input type="password" placeholder="Your account password" name="_password"><br>
                             <?php if ( isset( $passwordDErr ) ) echo "<div class='invalid'>" . $passwordDErr . "</div>"; ?>

                             <br><button class="btn bth-default btn-md update_button" style="background-color: red; color: white; border-color: black;" type="submit" name="submitDelete">Delete your account</button><?php echo "<div class='invalid'>" . $deleteD . "</div>"; ?>
                        </form>
                    </div>
            </div>
        </div>
    </body>
</html>
