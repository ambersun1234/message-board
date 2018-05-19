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

            $username = $email = $usernameErr = $emailErr = $oldPasswordErr = $newPasswordErr = $cnewPasswordErr = "";

            // get data
            $sql = "select * from account where username = '" . $_SESSION['user'] . "'";
            $query = mysqli_query( $con , $sql );
            $row = $query->fetch_assoc();

            $imageFilePath = $row["image"];
            if ( $imageFilePath == "" ) $imageFilePath = "default.jpeg"; // if personal image not specify , set to default

            $username = $row["username"];
            $email = $row["email"];
            // end get data

            if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset( $_POST["SUBMIT_signup"] ) ) { // active when submit

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
                         <?php echo "<div class='signUPvalid'>" . $usernameErr . "</div>"; ?>

                         Email:<br>
                         <input type="text" name="_email" placeholder="you@gmail.com" value="<?php echo $email; ?>"><br>
                         <?php echo "<div class='signUPvalid'>" . $emailErr . "</div>"; ?>

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


                         <button class="btn bth-default update_button" type="submit" name="fileToUpload">Update</button>
                     </div>
                </form>
            </div>
         </div>
    </body>
</html>
