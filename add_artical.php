<?php session_start(); ?>

<html lang="en">
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="custom.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <script type="text/javascript">
            $( document ).ready( function() {
                var boardid = "<?php echo $_GET["boardid"]; ?>";
                if ( boardid == "Gossip" || boardid == "News" || boardid == "Gaming" ) {

                }
                else {
                    $( "#invalidBoard" ).css( { "text-align" : "center" , "padding" : "20px" , "color" : "#ff0000" } );
                }
            });
        </script>

        <title>add new post</title>

    </head>

    <body  style="background-color: #f9f9f9;">
        <?php
            $location = "Location: displayBoard.php?boardid=" . $_GET["boardid"];

            if ( $_SESSION["loggedin"] == false ) {
                $location = "Location: displayBoard.php?boardid=" . $_GET["boardid"];
                header( $location );
            }
         ?>

        <?php
            $title = $article = $boardid = "";
            $titleErr = $articleErr = $postErr = "";

            if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset( $_POST["SUBMIT"] ) ) {
                include "connectToDB.php";

                $title = getData( $con , $_POST["_title"] );
                $article = getData( $con , $_POST["_artical"] );
                $boardid = getData( $con , $_POST["_boardid"] );

                checkTitle( $title );
                checkArticle( $article );

                if ( checkValid( $titleErr , $articleErr ) ) {

                    $id = findUserid( $con , $_SESSION["user"] );
                    $postid = findPostid( $con );

                    if ( boardidValid( $boardid ) != false && $postErr == "" ) {
                        // $article = str_replace( '&#13;' , '<br>' , $article );

                        $sql = "insert into post( userid , postid , title , article , boardid )";
                        $sql .= " value( " . $id . " , " . $postid . " , '" . $title . "' , '" . $article . "' , '" . $boardid . "' )";

                        $query = mysqli_query( $con , $sql );

                        if ( !$query ) $postErr = "Something went wrong , please try again...<br>";
                        else header("Location: /displayBoard.php?boardid=" . $boardid);
                    }
                }

                include "disconnectToDB.php";
            }
            function boardidValid( $board ) {
                if ( $board == "Gaming" || $board == "News" || $board == "Gossip" ) return true;
                else {
                    $GLOBALS["postErr"] = "Something went wrong , please try again...<br>";
                    return false;
                }
            }
            function findUserid( $con , $username ) {
                $sql = "select userid from account where username = '" . $username . "'";
                $query = mysqli_query( $con , $sql );
                if ( !$query ) $GLOBALS["postErr"] = "Something went wrong , please try again...<br>";
                $row = $query->fetch_assoc();
                return $row["userid"];
            }
            function findPostid( $con ) {
                $sql = "select max( postid ) as postid from post";
                $query = mysqli_query( $con , $sql );
                if ( !$query ) $GLOBALS["postErr"] = "Something went wrong , please try again...<br>";
                $row = $query->fetch_assoc();
                if ( $row["postid"] == null ) return 0;
                else return $row["postid"] + 1;
            }
            function getData( $con , $data ) {
                $data = stripslashes( $data ); // remove all \
                $data = htmlspecialchars( $data ); // turn &"'<> to real entity
                $data = mysqli_real_escape_string( $con , $data );
                // $data = str_replace( '\r' , '' , $data ); // replace new line
                // $data = str_replace( '\n' , '&#13;' , $data ); // replace new line
                return $data;
            }
            function checkTitle( $title ) {
                if ( $title == "" ) $GLOBALS["titleErr"] = "Title can not be blank!!<br>";
                else $GLOBALS["titleErr"] = "";
            }
            function checkArticle( $article ) {
                if ( $article == "" ) $GLOBALS["articleErr"] = "Article can not be blank!!<br>";
                else if ( strlen( $article ) < 10 ) $GLOBALS["articleErr"] = "Article must have at least 10 words!!<br>";
                else $GLOBALS["articleErr"] = "";
            }
            function checkValid( $titleErr , $articleErr ) {
                if ( $titleErr == "" && $articleErr == "" ) return true;
                else return false;
            }
         ?>

        <?php include "statusColumn.php"; ?>

        <div id="invalidBoard" class="add_artical">
        <?php
            if ( boardidValid( $_GET["boardid"] ) ) {
         ?>
                <div style="text-align: center; width: 100%; padding: 5px; margin: 10px; font-size: 22px;">
                    You are now posting to
                    <span style="font-style: oblique; color: #ff6060;">
                        <?php echo $_GET['boardid']; ?>
                    </span>
                    board
                </div>

                <form method="post" action="<?php echo htmlspecialchars( $_SERVER["PHP_SELF"] ) . '?boardid=' . $_GET['boardid'];?>">
                    title:<br>
                    <!-- note: textarea does not have value attribute , so php echo should write in the middle of textarea -->
                    <textarea name="_title" rows="1" cols="1" maxlength="1024"><?php echo $title; ?></textarea><br>
                    <?php echo "<div class='invalid'>" . $titleErr . "</div>"; ?>

                    <br>
                    article:<br>
                    ( article must have at least 10 words )<br>
                    <textarea name="_artical" rows="15"><?php echo nl2br( $article ); ?></textarea><br>
                    <?php echo "<div class='invalid'>" . $articleErr . "</div>"; ?>

                    <!-- pass boardid to php after submit form -->
                    <input type="hidden" name="_boardid" value="<?php echo $_GET['boardid'];?>">

                    <br>
                    <button type="submit" name="SUBMIT" class="btn btn-default" style="background-color: #ff6060; color: #ffffff;">Submit</button>
                    <button type="reset" class="btn btn-default">Reset</button>
                    <?php echo "<div class='invalid'>" . $postErr . "</div>"; ?>
                </form>
        <?php
            }
            else {
         ?>
                <span style="font-size: 20px; font-style: oblique;">Invalid board , please don't change URL parameter<br></span>
        <?php
            }
         ?>
        </div>
    </body>
</html>
