<?php session_start(); ?>
<html lang="en">
<html>
    <head>
        <meta charset="utf-8">

        <link href="custom.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <title><?php echo $_GET["boardid"]; ?></title>

    </head>
    <body style="background-color: #f9f9f9;">
        <?php include "statusColumn.php" ?>

        <div class="col-xs-9 post">
            <br>
            <?php
                include "connectToDB.php";

                $boardid = getData( $con , $_GET["boardid"] );

                // find all post in gaming
                $sql = "select userid , postid , title , date_time as time from post where boardid = '" . $boardid . "' order by time DESC";
                $query = mysqli_query( $con , $sql );

                if ( $query->num_rows > 0 ) { // post found
                    while ( $row = $query->fetch_assoc() ) { // show all post
                        $username = getUsername( $con , $row["userid"] );
                        echo "<div class='postview'>";
                            echo '<a href="/displayPost.php?var1=' . $row['postid'] . '&title=' . $row['title'] . '">' . $row['title'] . '</a><br>';
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
                function getData( $con , $data ) {
                    $data = stripslashes( $data ); // remove all \
                    $data = htmlspecialchars( $data ); // turn &"'<> to real entity
                    $data = mysqli_real_escape_string( $con , $data );
                    return $data;
                }
                echo '<button type="button" class="btn btn-primary btn-xs" style="color:black; background-color:white; position:relative; top:10px; font-size:20px;"><img src= "back.png">back</button>'.'&nbsp&nbsp&nbsp&nbsp&nbsp';
                echo "<span style='float: right;'>";
                echo '<button type="button" class="btn btn-primary btn-xs" style=" color:black; background-color:white; position:relative; top:10px;font-size:20px;">next <img src= "next.png"></button>';
             ?>
        </div>
        <div class="col-xs-3">
            <br>
            <button type="button" class="btn btn-default" onclick="location.href='add_artical.php?var1=<?php echo $boardid; ?>'" style="background-color: #ff7474; color: black; font-size:5pt; position:relative; top:10px;">New post<img src="edit.png"></button>
        </div>

    </body>
</html>
