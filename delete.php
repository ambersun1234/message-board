<?php
    session_start();

    echo "<link href='custom.css' rel='stylesheet'>"; // include css file

    include "connectToDB.php";

    $postid = getData( $con , $_GET["postid"] ); // get variable from url parameter
    $id = getData( $con , $_GET["id"] );

    echo "postid = " . $postid . "<br>";

    $sql = "select commentid from comment where postid = " . $postid; // select all comment id
    $query = mysqli_query( $con , $sql );
echo "sql = " . $sql . "<br>";
    if ( $query ) {
        if ( $query->num_rows > 0 ) { // comment found

            while ( $row = $query->fetch_assoc() ) {
                $commentid = $row["commentid"];

                $sql2 = "select replyid from reply where commentid = " . $commentid;
                $query2 = mysqli_query( $con , $sql2 );

                if ( $query2 ) {
                    if ( $query2->num_rows > 0 ) { // reply found

                        while ( $row2 = $query2->fetch_assoc() ) {
                            $replyid = $row2["replyid"];

                            $sql3 = "delete from reply where replyid = " . $replyid;
                            $query3 = mysqli_query( $con , $sql3 ); // delete reply
                        }
                        $sql2 = "delete from comment where commentid = " . $commentid;
                        $query2 = mysqli_query( $con , $sql2 ); // delete comment
                    }
                    else {
                        $sql2 = "delete from comment where commentid = " . $commentid;
                        $query2 = mysqli_query( $con , $sql2 ); // delete comment
                    }
                }
                else echo "<div class='invalid'>Something went wrong , please reload the page again.<br></div>";

            }
            $sql = "delete from post where postid = " . $postid;
            $query = mysqli_query( $con , $sql ); // delete post
        }
        else {
            $sql = "delete from post where postid = " . $postid;
            $query = mysqli_query( $con , $sql ); // delete post
        }
    }
    else echo "<div class='invalid'>Something went wrong , please reload the page again.<br></div>";

    header("Location: /view.php?id=" . $id . "&which=post&sort=apt");
    // job done

    include "disconnectToDB.php";

    function getData( $con , $data ) {
        $data = stripslashes( $data ); // remove all \
        $data = htmlspecialchars( $data ); // turn &"'<> to real entity
        $data = mysqli_real_escape_string( $con , $data );
        return $data;
    }
 ?>
