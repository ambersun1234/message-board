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

        <script>
            function hideShow( id ) {
                var x = document.getElementById("lalaland" + id );

                if ( x.style.display === "none" ) {
                    x.style.display = "block";
                }
                else {
                    x.style.display = "none";
                }
            }
            function addTitle( title ) {
                $( "title" ).text( title );
            }
            $( document ).ready( function() {
                if ( check ) {
                    $( "#invalidPost" ).css( { "text-align" : "center" , "padding" : "20px" , "color" : "#ff0000" } );
                }

                $( "span.changeColor" ).each( function() {
                    var str = $( this ).text();

                    if ( str.charAt( 0 ) == "(" ) {
                        var color = str.substring( 1 , str.indexOf( ")" ) );

                        if ( !color.includes( "<script" ) ) {
                            var storeStr = str.substring( str.indexOf( ")" ) + 1 );

                            $( this ).text( storeStr );
                            $( this ).css( 'color' , color );
                        }
                    }
                });
            });
        </script>

        <title></title>

    </head>

    <body>
        <body style="background-color: #f9f9f9;">
        <script>
            function showDiv() {
                document.getElementById('welcomeDiv').style.display = "block";
            }
        </script>
        <?php include "statusColumn.php"; ?>

        <?php // common part
            include "connectToDB.php";

            $title = $username = $time = $postid = $article = $userid = "";

            $postid = getData( $con , $_GET["postid"] );

            $sql = "select a.username , a.userid , p.date_time , p.article , p.title from account as a , post as p where p.postid = " . $postid . " and p.userid = a.userid";
            $query = mysqli_query( $con , $sql );
            $row = $query->fetch_assoc();

            $postidValid = $query->num_rows;
            if ( $postidValid != 1 ) {
         ?>
                <script type="text/javascript">var check = true;</script>
        <?php
            }
            else {
         ?>
                <script type="text/javascript">var check = false;</script>
        <?php
            }
         ?>
            <script type="text/javascript">addTitle( "<?php echo $row["title"]; ?>" );</script>
        <?php

            $title = $row["title"];
            $username = $row["username"];
            $time = $row["date_time"];
            $userid = $row["userid"];
            $article = $row["article"];

            $article = str_replace( "(::" , "<span class=\"changeColor\">" , $article );
            $article = str_replace( "::)" , "</span>" , $article );

            include "disconnectToDB.php";

            function getData( $con , $data ) {
                $data = stripslashes( $data ); // remove all \
                $data = htmlspecialchars( $data ); // turn &"'<> to real entity
                $data = mysqli_real_escape_string( $con , $data );
                // $data = str_replace( '\r' , '' , $data ); // replace new line
                // $data = str_replace( '\n' , '&#13;' , $data ); // replace new line
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

                $comment = str_replace( "(::" , "<span class=\"changeColor\">" , $comment );
                $comment = str_replace( "::)" , "</span>" , $comment );

                $sql = "insert into comment( userid , postid , commentid , text ) ";
                $sql .= "value( " . $userid . " , " . $postid . " , " . $commentid . " , '" . $comment . "' )";

                $query = mysqli_query( $con , $sql );
                if ( !$query ) $commentErr = "comment failed , please try again...<br>"; // insert failed
                else {
                    $comment = "";
                    header("Location: /displayPost.php?postid=" . $postid . "&title=" . $title );
                }

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

          <?php // reply
                $reply = $replyErr = "";

                if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset( $_POST["submit_reply"] ) ) {
                    include "connectToDB.php";

                    $userid = getUserid( $con , $_SESSION["user"] );
                    $commentid = getData( $con , $_GET["commentid"] );
                    $reply = getData( $con , $_POST["_reply"] );
                    $replyid = getReplyid( $con );

                    $reply = str_replace( "(::" , "<span class=\"changeColor\">" , $reply );
                    $reply = str_replace( "::)" , "</span>" , $reply );

                    $sql = "insert into reply( userid , commentid , replyid , text ) ";
                    $sql .= "value( " . $userid . " , " . $commentid . " , " . $replyid . " , '" . $reply .  "' )";
                    $query = mysqli_query( $con , $sql );

                    if ( !$query ) $replyErr = "comment failed , please try again...<br>"; // insert failed
                    else {
                        $reply = "";
                        header("Location: /displayPost.php?postid=" . $postid . "&title=" . $title );
                    }

                    include "disconnectToDB.php";
                }
                function getReplyid( $con ) {
                    $sql = "select max( replyid ) as replyid from reply";
                    $query = mysqli_query( $con , $sql );
                    $row = $query->fetch_assoc();

                    return $row["replyid"] + 1;
                }
           ?>

         <br>
         <div id="invalidPost" class="displayPost">
             <?php
                // query post's comment
                include "connectToDB.php";

                $sql = "select a.username , a.image , p.postid , c.date_time , c.text , c.commentid ";
                $sql .= "from post p , comment c , account a " ;
                $sql .= "where p.postid = " . $postid . " and p.postid = c.postid and c.userid = a.userid ";

                $query = mysqli_query( $con , $sql );
                $count = 0;

                if ( $postidValid ) { // find post's comment
             ?>
                     <div class="row">
                         <!-- display post basic information -->
                         <div class="col-xs-1" style="background-color: #ffd460">
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

                     <!-- display post article -->
                     <div class="row">
                         <div class="col-xs-1">
                         </div>
                         <div class="col-xs-11" style="padding-top: 15px; padding-bottom: 15px;">
                             <?php echo nl2br( $article ); ?>
                         </div>
                     </div>

                     <hr style="margin-bottom: 15px; border-width: 4px; border-color: #ffce94;">
            <?php
                    while ( $row = $query->fetch_assoc() ) {
                        $count++;

                        if ( $row["image"] == "" ) $image = "default.jpeg";
                        else $image = $row["image"];

             ?>
                        <div class="eachCommentSection" style="margin-bottom: 15px; padding: 10px; border-radius: 25px; border: 2px solid #ffe08f;">
                            <!-- display post's comment -->
                            <div class="row">
                                <div class="col-xs-2">
                                    <img src='/images/<?php echo $image; ?>' alt='Profile picture' height='30' width='30'>
                                    <?php echo $row["username"] . " : "; ?>
                                </div>
                                <div class="col-xs-10">
                                    <div style="float: right">
            <?php
                                        if ( isset( $_SESSION['loggedin'] ) && $_SESSION['loggedin'] == true ) {
             ?>
                                            <button type="button" class="btn btn-primary btn-md commentButton" onclick="hideShow( <?php echo $count; ?> )"><img src="/images/reply.png"></button>
            <?php
                                        }
             ?>
                                        <?php echo $row["date_time"]; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-1">
                                </div>
                                <div class="col-xs-11">
                                    <?php echo nl2br( $row["text"] ); ?>
                                </div>
                            </div>
            <?php
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
             ?>
                                    <!-- display comment's comment -->
                                    <div class="row">
                                        <div class="col-xs-1">
                                        </div>
                                        <div class="col-xs-2">
                                            <img src='/images/<?php echo $image; ?>' alt='Profile picture' height='30' width='30'>
                                            <?php echo $row["username"] . " : "; ?>
                                        </div>
                                        <div class="col-xs-9">
                                            <div style="float: right;">
                                                <?php echo $row["date_time"]; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-2">
                                        </div>
                                        <div class="col-xs-10">
                                            <?php echo nl2br( $row["text"] ); ?>
                                        </div>
                                    </div>
            <?php
                                } // end find comment's comment
                            }
             ?>
                            <!-- reply's input text field -->
                            <div style="display: none;" id="lalaland<?php echo $count; ?>" class="row">
                                <div class="col-xs-1">
                                </div>
                                <div class="col-xs-2">
                                    <img src="/images/<?php echo $_SESSION["image"]; ?>" alt="Profile picture" height="30" width="30">
                                    <?php echo $_SESSION["user"] . " : "; ?>
                                </div>
                                <div class="col-xs-9">
                                    <form method="post" action="<?php echo htmlspecialchars( $_SERVER["PHP_SELF"] ) . '?postid=' . $postid . '&title=' . $title . '&commentid=' . $commentid; ?>">
                                        <textarea style="background-color: #FFF0D4;width: 100%;padding: 5px;" name="_reply" rows="3" placeholder="enter your reply"><?php echo $reply; ?></textarea>
                                        <?php if ( $replyErr != "" ) echo '<div class="invalid">' . $replyErr . '</div>'; // show error when reply failed ?>
                                        <button type="submit" name="submit_reply" class="btn btn-default" style="background-color: #ff6060; color: #ffffff;">Add reply</button>
                                    </form>
                                </div>
                            </div>
                        </div><!-- eachCommentSection -->
            <?php
                    } // end find post's comment
                } // end postid valid
                else {
             ?>
                    <span style="font-size: 20px; font-style: oblique;">Invalid post , please don't change URL parameter<br></span>
            <?php
                }
                include "disconnectToDB.php";
             ?>

            <?php
                include "connectToDB.php";
                if ( isset( $_SESSION['loggedin'] ) && $_SESSION['loggedin'] == true ) {
             ?>
                    <!-- post comment -->
                    <div class="row">
                        <div class="col-xs-2" style="padding: 0px;">
                            <img style="vertical-align: baseline;" src="/images/<?php echo $_SESSION["image"]; ?>" height="45" weight="35">
                            <?php echo $_SESSION["user"] . " : "; ?>
                        </div>
                        <div class="col-xs-10" style="padding: 0px;">
                            <form method="post" action="<?php echo htmlspecialchars( $_SERVER["PHP_SELF"] ) ?>?postid=<?php echo $postid; ?>&title=<?php echo $title; ?>">
                                <textarea style="background-color: #FFF0D4;width: 100%;padding: 5px;" name="_comment" rows="3" placeholder="enter your comment"><?php echo $comment; ?></textarea>
            <?php
                                if ( $commentErr != "" ) {
             ?>
                                    <div class="invalid"><?php echo $commentErr; ?></div>
                                    <!-- show error when comment failed -->
            <?php
                                }
             ?>
                                <button type="submit" name="submit_comment" class="btn btn-default" style="background-color: #ff6060; color: #ffffff;">Add comment</button>
                            </form>
                        </div>
                    </div>
            <?php
                }
                include "disconnectToDB.php";
             ?>
         </div>
    </body>
</html>
