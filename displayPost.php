<?php session_start(); ?>

<html lang="en">
<html>
    <head>
        <meta charset="utf-8">

        <link href="custom.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <title><?php echo $_GET["title"]; ?></title>

    </head>

    <body>
        <?php include "statusColumn.php"; ?>
        <div class="displayPost">
            <?php
                include "connectToDB.php";

                $title = $username = $time = $id = "";

                $title = $_GET["title"];
                $id = getData( $con , $_GET["var1"] );

                $sql = "select a.username , p.date_time , p.article from account as a , post as p where p.postid = " . $id . " and p.userid = a.userid";
                $query = mysqli_query( $con , $sql );
                $row = $query->fetch_assoc();

                $username = $row["username"];
                $time = $row["date_time"];

                echo "Title : ". $title . "<br><br>";
                echo "Author: " . $username . "<br><br>";
                echo "Time  : " . $time . "<br>";

                echo "<hr style='border-width: 3px;'>";

                echo $row["article"] . "<br>";

                include "disconnectToDB.php";

                function getData( $con , $data ) {
                    $data = stripslashes( $data ); // remove all \
                    $data = htmlspecialchars( $data ); // turn &"'<> to real entity
                    $data = mysqli_real_escape_string( $con , $data );
                    $data = str_replace( '\r\n' , '<br>' , $data ); // replace new line
                    return $data;
                }
             ?>
        </div>
    </body>
</html>
