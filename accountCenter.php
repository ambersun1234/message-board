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
            if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset( $_POST["SUBMIT_signup"] ) ) { // active when submit
                include "connectToDB.php";

                include "disconnectToDB.php";
            }
         ?>

         <div class="accountCenter">
             <form method="post" action="<?php echo htmlspecialchars( $_SERVER["PHP_SELF"]);?>">
                 <div class="container row">
                     <div class="col-xs-6">
                     </div>
                     <div class="col-xs-6">
                     </div>
                 </div>
             </form>
         </div>
    </body>
</html>
