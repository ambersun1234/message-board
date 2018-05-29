<?php session_start(); ?>

<html lang="en">
<html>
    <head>
        <meta charset="utf-8">

        <link href="custom.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!--this is google arvo fonts and font awesome-->
        <link href="https://fonts.googleapis.com/css?family=Arvo" rel="stylesheet">
        <link rel="stylesheet" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <title><?php echo $_GET["title"]; ?></title>

    </head>

    <body>
        <body style="background-color: #f9f9f9;">
        <?php include "statusColumn.php"; ?>

        <?php
            include "connectToDB.php";

            $title = $username = $time = $postid = $article = $userid = "";

            $title = $_GET["title"];
            $postid = getData( $con , $_GET["var1"] );

            $sql = "select a.username , a.userid , p.date_time , p.article from account as a , post as p where p.postid = " . $postid . " and p.userid = a.userid";
            $query = mysqli_query( $con , $sql );
            $row = $query->fetch_assoc();

            $username = $row["username"];
            $time = $row["date_time"];
            $userid = $row["userid"];
            $article = $row["article"];

            include "disconnectToDB.php";

            function getData( $con , $data ) {
                $data = stripslashes( $data ); // remove all \
                $data = htmlspecialchars( $data ); // turn &"'<> to real entity
                $data = mysqli_real_escape_string( $con , $data );
                $data = str_replace( '\r\n' , '<br>' , $data ); // replace new line
                return $data;
            }
         ?>

         <br>
         <div class="displayPost">
             <div class="row">
                 <div class="col-xs-1" style="background-color: red"> <!-- display post basic information -->
                     <span class="white_space">Title<br></span>
                     <span class="white_space">Author<br></span>
                     <span class="white_space">Time<br></span>
                 </div>
                 <div class="col-xs-11" style="background-color: #b3a6fa";>
                     <?php echo $title; ?><br>
                     <?php echo $username; ?><br>
                     <?php echo $time; ?><br>
                 </div>
             </div>
             <hr style="border-width: 3px; border-color: #f9f9f9;">

             <?php echo $article . "<br><br><br>"; ?> <!-- display post article -->

             <hr style="border-width: 4px; border-color: #ff6060;">

             <?php
                include "connectToDB.php";

                $sql = "select a.username , a.image , p.postid , c.date_time , c.text , c.commentid ";
                $sql .= "from post p , comment c , account a " ;
                $sql .= "where p.postid = " . $postid . " and p.postid = c.postid and c.userid = a.userid ";

                $query = mysqli_query( $con , $sql );

                if ( $query->num_rows > 0 ) { // find post's comment
                    while ( $row = $query->fetch_assoc() ) {
                        if ( $row["image"] == "" ) $image = "default.jpeg";
                        else $image = $row["image"];

                        // display post's comment
                        echo "<img src='/images/" . $image . "' alt='Profile picture' height='30' width='30'>" . $row["username"] . " : " . $row["text"] . " -- " . $row["date_time"] . "&nbsp&nbsp&nbsp". '<button type="button" class="btn btn-primary btn-xs" style="background-color:">reply</button>'."<br>";

                        // fetch commentid
                        $commentid = $row["commentid"];

                        // query comment's comment
                        $sql = "select r.date_time , r.text , a.username , a.image ";
                        $sql .= "from comment as c , reply as r , account as a ";
                        $sql .= "where c.commentid = " . $commentid . " and c.commentid = r.commentid and a.userid = r.userid";

                        $query2 = mysqli_query( $con , $sql );
                        if ( $query2->num_rows > 0 ) { // find comment's comment
                            while ( $row = $query2->fetch_assoc() ) {
                                if ( $row["image"] == "" ) $image = "default.jpeg";
                                else $image = $row["image"];

                                // display comment's comment
                                echo "<span class='white_space'>           <img src='/images/" . $image . "' alt='Profile picture' height='30' width='30'>" . $row["username"] . " : " . $row["text"] . " -- " . $row["date_time"] ."<br></span>";
                            } // end find comment's comment
                        }
                    } // end find post's comment
                }
                else echo "There is no comment yet.<br>";

                include "disconnectToDB.php";
              ?>
         </div>
    </body>
</html>
