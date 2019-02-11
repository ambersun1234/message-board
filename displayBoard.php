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

        <script type="text/javascript">
            $( document ).ready( function() {
                var boardid = "<?php echo htmlspecialchars( $_GET["boardid"] ); ?>";
                var search = "<?php echo htmlspecialchars( $_GET["search"] ); ?>";

                $( "#query input" ).val( search );
                
                if ( boardid == "Gossip" || boardid == "News" || boardid == "Gaming" ) {

                }
                else {
                    $( "#invalidBoard" ).css( { "text-align" : "center" , "padding" : "20px" , "color" : "#ff0000" } );
                }
            });
            function searchQuery() {
                var query = $( "#query input" ).val();
                window.location.href = "./displayBoard.php?boardid=<?php echo htmlspecialchars( $_GET["boardid"] ); ?>&search=" + query;
            }
        </script>

    </head>
    <body style="background-color: #f9f9f9;">

        <div id="invalidBoard" style="width: 65%; margin: 0 auto;">
            <div style="padding: 5px 5px;">
                <?php if ( $_SESSION["loggedin"] == true && boardidValid( $_GET["boardid"] ) ) { ?>
                    <br>
                    <button type="button" class="btn btn-default" onclick="location.href='add_artical.php?boardid=<?php echo htmlspecialchars( $_GET["boardid"] ); ?>'" style="background-color: #ff7474; color: black; font-size:15px; position:relative;">New post<img src="/images/edit.png"></button>
                <?php } ?>
            </div>

            <div style="width: 80%; margin: auto;">
                <form id="query" class="row" action="javascript:searchQuery();">
                    <input type="text" class="col-xs-11" style="padding: 10px; height: 35px; size: 15px;" placeholder="Search with title or name">
                </form>
            </div>

            <?php include "statusColumn.php" ?>

            <?php
                include "connectToDB.php";

                $boardid = getData( $con , $_GET["boardid"] );

                $searchCheck = false;
                if ( isset( $_GET["search"] ) ) {
                    $search = getData( $con , $_GET["search"] );
                    $searchCheck = true;
                    $sql = "SELECT DISTINCT p.userid , p.postid , p.title , p.date_time as time from post as p , account as a where p.boardid = '" . $boardid . "' and p.userid = a.userid ";
                    $sql .= "AND ( p.title LIKE '%" . $search . "%' OR a.username LIKE '%" . $search . "%' )";
                    $sql .= "order by time DESC";
                    $query = mysqli_query( $con , $sql );
                }
                else {
                    // find all post in gaming
                    $sql = "select userid , postid , title , date_time as time from post where boardid = '" . $boardid . "' order by time DESC";
                    $query = mysqli_query( $con , $sql );
                }

                if ( $query->num_rows > 0 && boardidValid( $boardid ) ) { // post found
                    while ( $row = $query->fetch_assoc() ) { // show all post
                        $username = getUsername( $con , $row["userid"] );
             ?>             <div class='postview'>
                            <a href="/displayPost.php?postid=<?php echo $row['postid']; ?>"><?php echo $row['title']; ?></a><br>
                            <p style='text-align: left;'><!-- same line but left -->
                                <?php echo $username; ?>
                            <span style='float: right;'><!-- same line but right -->
                                <?php echo $row["time"]; ?>
                            </span></p>
                        </div>
            <?php
                    }

                }
                else {
                    if ( boardidValid( $boardid ) && !$searchCheck ) {
             ?>
                        <span style="font-size: 20px; font-style: oblique;">There is no post yet!!<br></span>
            <?php
                    }
                    else if ( boardidValid( $boardid ) && $searchCheck ) {
             ?>
            <?php
                    }
                    else {
             ?>
                        <span style="font-size: 20px; font-style: oblique;">Invalid board , please don't change URL parameter<br></span>
            <?php
                    }
                }
                function boardidValid( $boardid ) {
                    $valid = false;
                    if ( $boardid == "Gossip" || $boardid == "News" || $boardid == "Gaming" ) {
                        $valid = true;
                    }
                    return $valid;
                }
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
             ?>
        </div>
    </body>
</html>
