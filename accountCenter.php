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

            // get personal image
            $sql = "select image from account where username = '" . $_SESSION['user'] . "'";
            $query = mysqli_query( $con , $sql );
            $row = $query->fetch_assoc();
            $imageFilePath = $row["image"];
            if ( $imageFilePath == "" ) $imageFilePath = "default.jpeg"; // if personal image not specify , set to default
            // end get personal image

            if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset( $_POST["SUBMIT_signup"] ) ) { // active when submit

            }
            include "disconnectToDB.php";
         ?>

         <div class="accountCenter">
             <div class="container">
                <form method="post" action="<?php echo htmlspecialchars( $_SERVER["PHP_SELF"]);?>">
                     <div class="col-xs-3" style="border-color: green;border-style: solid;border-width: medium; padding: 5px 5px;">
                         <?php echo "<img src='/images/" . $imageFilePath . "'>"?>
                         <?php echo "<h2>" . $_SESSION['user'] . "</h2>"?>
                         <input accept="image/jpeg" type="file" name="fileToUpload"><br>
                         <button class="btn bth-default photo_button" type="submit" name="fileToUpload">Update</button>
                     </div>
                     <div class="col-xs-8" style="border-color: blue;border-style: solid;border-width: medium;">
                         <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                     </div>
                </form>
            </div>
         </div>
    </body>
</html>
