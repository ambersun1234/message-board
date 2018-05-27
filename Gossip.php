<?php session_start(); ?>
<html lang="en">
<html>
    <head>
        <meta charset="utf-8">

        <link href="custom.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <title>News board</title>

    </head>
    <body style="background-color: #f9f9f9;">
        <?php include "statusColumn.php" ?>

        <div class="col-xs-9 post">
            <br>
            <?php
                include "connectToDB.php";

                // find all post in gaming
                $sql = "select userid , postid , title , date_time as time from post where boardid = 'Gossip' order by time DESC";
                $query = mysqli_query( $con , $sql );

                if ( $query->num_rows > 0 ) { // post found
                    while ( $row = $query->fetch_assoc() ) { // show all post
                        $username = getUsername( $con , $row["userid"] );
                        echo "<div class='postview'>";
                            echo "<a href='/displayPost.php?var1=" . $row["postid"] . "'>" . $row["title"] . "</a><br>";
                            echo "<p style='text-align: left;'>"; // same line but left
                                echo $username;
                            echo "<span style='float: right;'>"; // same line but right
                                echo $row["time"];
                            echo "</span></p>";
                        echo "</div>";
                    }
                }
                else echo "There is no post yet!!<br>";

                function getUsername( $con , $id ) {
                    $sql = "select username from account where userid = '" . $id . "'";
                    $query = mysqli_query( $con , $sql );
                    $row = $query->fetch_assoc();
                    return $row["username"];
                }

                include "disconnectToDB.php";
             ?>
        </div>
        <div class="col-xs-3">
            <br>
            <button type="button" class="btn btn-default" onclick="location.href='add_artical.php?var1=Gossip'" style="background-color: #ff6060; color: #ffffff;">add new post</button>
        </div>

    </body>
</html>
