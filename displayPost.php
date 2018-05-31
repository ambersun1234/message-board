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
        <script>
            function showDiv() {
                document.getElementById('welcomeDiv').style.display = "block";
            }
        </script>
        <?php include "statusColumn.php"; ?>

        <?php
            include "connectToDB.php";

            $title = $username = $time = $postid = $article = $userid = "";

            $title = getData( $con , $_GET["title"] );
            $postid = getData( $con , $_GET["postid"] );

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
                $data = str_replace( '\r' , '' , $data ); // replace new line
                $data = str_replace( '\n' , '&#13;' , $data ); // replace new line
                return $data;
            }
         ?>

         <?php // comment part
            $comment = $commentErr = "";

            if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset( $_POST["submit_comment"] ) ) { // active when submit
                include "connectToDB.php";

                $userid = getUserid( $con , $_SESSION["user"] );
                $commentid = getCommentid( $con );
                $comment = getData( $con , $_POST["_comment"] );

                $comment = str_replace( '&#13;' , '<br>' , $comment );

                $sql = "insert into comment( userid , postid , commentid , text ) ";
                $sql .= "value( " . $userid . " , " . $postid . " , " . $commentid . " , '" . $comment . "' )";

                $query = mysqli_query( $con , $sql );
                if ( !$query ) $commentErr = "comment failed , please try again...<br>"; // insert failed
                else header("Location: /displayPost.php?postid=" . $postid . "&title=" . $title );

                include "disconnectToDB.php";
            }
            function getCommentid( $con ) {
                $sql = "select max( commentid ) as commentid from comment";
                $query = mysqli_query( $con , $sql );
                $row = $query->fetch_assoc();
                return $row["commentid"] + 1;
            }
            function getUserid( $con , $name ) {
                $sql = "select userid from account where username = '" . $name . "'";
                $query = mysqli_query( $con , $sql );
                $row = $query->fetch_assoc();
                return $row["userid"];
            }
          ?>
         <br>
         <div class="displayPost">
             <div class="row">
                 <div class="col-xs-1" style="background-color: #ffd460"> <!-- display post basic information -->
                     <span class="white_space">Title<br></span>
                     <span class="white_space">Author<br></span>
                     <span class="white_space">Time<br></span>
                 </div>
                 <div class="col-xs-11" style="background-color: #ffe99e";>
                     <?php echo $title; ?><br>
                     <?php echo $username; ?><br>
                     <?php echo $time; ?><br>
                 </div>
             </div>
             <hr style="border-width: 3px; border-color: #f9f9f9;">

             <?php echo $article . "<br><br><br>"; ?> <!-- display post article -->

             <hr style="border-width: 4px; border-color: #ffce94;">

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
                        echo "<img src='/images/" . $image . "' alt='Profile picture' height='30' width='30'>" . $row["username"] . " : " . $row["text"] . "<span style='float: right'> " . $row["date_time"] . "&nbsp&nbsp&nbsp". '<button type="button" class="btn btn-primary btn-xs" style="position:relative;bottom:4px; background-color:#f9f9f9"><img src="/images/reply.png"></button>'."</span><br>";

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
                                echo "<span class='white_space'>";
                                echo "        ";
                                echo "<img src='/images/" . $image . "' alt='Profile picture' height='30' width='30'>" . $row["username"] . " : " . $row["text"] . "<span style='float: right;'>" . $row["date_time"] ."        </span><br></span>";
                            } // end find comment's comment

                        }
                        echo '<hr style="border-width: 2px; border-color: #3e3831;">';
                    } // end find post's comment
                }
                include "disconnectToDB.php";
             ?>
            <?php
                include "connectToDB.php";
                if ( isset( $_SESSION['loggedin'] ) && $_SESSION['loggedin'] == true ) {
                    echo '<div class="row">';
                        echo '<div class="col-xs-1" style="padding: 0px;">';
                            echo '<img style="vertical-align: baseline;" src="/images/' . $_SESSION["image"] . '"height="45" weight="35">';
                        echo '</div>';
                        echo '<div class="col-xs-11" style="padding: 0px;">';
                            echo '<form method="post" action="' . htmlspecialchars( $_SERVER["PHP_SELF"] ) . '?postid=' . $postid . '&title=' . $title . '">';
                                echo '<textarea style="background-color: #FFF0D4;width: 100%;padding: 5px;" name="_comment" rows="3" placeholder="enter your comment">' . $comment . '</textarea>';
                                if ( $commentErr != "" ) echo '<div class="invalid">' . $commentErr . '</div>'; // show error when comment failed
                                echo '<button type="submit" name="submit_comment" class="btn btn-default" style="background-color: #ff6060; color: #ffffff;">Add comment</button>';
                            echo '</form>';
                        echo '</div>';
                    echo '</div>';
                }
                include "disconnectToDB.php";
             ?>

         </div>
    </body>
</html>
