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
            var boardid = "<?php echo htmlspecialchars( $_GET["boardid"] ); ?>";
            var search = "<?php echo htmlspecialchars( $_GET["search"] ); ?>";
            var pageid = "<?php echo htmlspecialchars( $_GET["page"] ); ?>";

            function pageJump( side , pageNumber ) {
                // current > total
                pageid = pageid > pageNumber ? pageNumber : pageid;
                // current < total
                pageid = pageid < 1 ? 1 : pageid;

                if ( search == "" ) {
                    window.location.href = "./displayBoard.php?boardid=" + boardid + "&page=" + ( side > 0 ? ++pageid : --pageid );
                }
                else {
                    window.location.href = "./displayBoard.php?boardid=" + boardid + "&search=" + search + "&page=" + ( side > 0 ? ++pageid : --pageid );
                }
            }
            function searchQuery() {
                var query = $( "#query input" ).val();
                if ( query != "" ) {
                    window.location.href = "./displayBoard.php?boardid=" + boardid + "&search=" + query + "&page=1";
                }
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

            <div style="width: 70%; margin: auto;">
                <form id="query" class="row" action="javascript:searchQuery();">
                    <input type="text" class="col-xs-11" style="padding: 10px; width: 100%; height: 35px; size: 15px;" placeholder="Search with title or name">
                </form>
            </div>

            <?php include "statusColumn.php" ?>

            <?php
                include "connectToDB.php";

                $boardid = getData( $con , $_GET["boardid"] );
                $pageid = getData( $con , $_GET["page"] );

                $searchCheck = false;
                if ( isset( $_GET["search"] ) ) {
                    $search = getData( $con , $_GET["search"] );
                    $searchCheck = true;
                    $sql = "SELECT DISTINCT p.userid , p.postid , p.title , p.date_time as time from post as p , account as a where p.boardid = '" . $boardid . "' and p.userid = a.userid ";
                    $sql .= "AND ( p.title LIKE '%" . $search . "%' OR a.username LIKE '%" . $search . "%' )";
                    $sql .= "order by time DESC ";
                }
                else {
                    // find all post in gaming
                    $sql = "select userid , postid , title , date_time as time from post where boardid = '" . $boardid . "' order by time DESC ";
                }
                $totalPost = getTotalPost( $con , $sql );

                // make sure not out of range
                if ( ( $pageid - 1 ) * 5 >= $totalPost ) {
                    $temp = ( $pageid - 1 ) * 5 - ( $totalPost - 5 );
                    $temp = floor( $temp / 5 );
                    $pageid -= $temp;
                }
                if ( $pageid < 1 ) {
                    $pageid = 1;
                }

                $sql .= "LIMIT " . ( $pageid - 1 ) * 5 . " , 5";
                $query = mysqli_query( $con , $sql );

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
                    <div style="text-align: center; width: 100%; margin: auto;">
                        <span style="font-size: 20px; font-style: oblique;">There is no post yet!!<br></span>
                    </div>
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
                if ( $totalPost > 0 ) {
             ?>
                    <!-- pagination part -->
                    <div style="width: 80%; margin: auto; text-align: center; padding-bottom: 10px;">
            <?php
                        $pagination1 = 5;
                        $pagination = $pagination1 - 1;
                        $pageNumber = ceil( $totalPost / $pagination1 );

                        // pagination arrangement
                        if ( $pageid < $pagination ) {
                            $calculate = 1;
                        }
                        else {
                            $temp = $pageNumber - $pagination;
                            $calculate = $pageid - $pagination + 1 > $temp ? $temp : $pageid - $pagination + 1;
                        }

                        for ( $index = $calculate , $count = 0 ; $index <= $pageNumber && $count < $pagination1 ; $index++ , $count++ ) {
             ?>
                            <a class="pagination" data-value="<?php echo $index; ?>" href="#"><?php echo $index; ?></a>
            <?php
                        }
             ?>
                    </div>


                    <div style="width: 80%; margin: auto; text-align: center;">
                        <button id="previousBtn" type="button" onclick="javascript:pageJump( -1 , <?php echo $pageNumber; ?> )" class="btn" style="width: 100px; margin-left: 10px; margin-right: 10px; padding-left: 10px; padding-right: 10px;">previous</button>
                        <button id="nextBtn" type="button" onclick="javascript:pageJump( 1 , <?php echo $pageNumber; ?> )" class="btn" style="width: 100px; margin-left: 10px; margin-right: 10px; padding-left: 10px; padding-right: 10px;">next</button>
            <?php
                    if ( $pageid == 1 ) {
             ?>
                        <script type="text/javascript">
                            $( "#previousBtn" ).prop( "disabled" , true );
                        </script>
            <?php
                    }
                    if ( $pageid >= ceil( $totalPost / 5 ) ) {
             ?>
                         <script type="text/javascript">
                            $( "#nextBtn" ).prop( "disabled" , true );
                         </script>
            <?php
                    }
             ?>
                    </div>
            <?php
                }
                function getTotalPost( $con , $sql ) {
                    $query = mysqli_query( $con , $sql );
                    return $query->num_rows;
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
<script type="text/javascript">
    $( "a.pagination[data-value='<?php echo $pageid; ?>']" ).css( { "color" : "black" } );

    if ( boardid == "Gossip" || boardid == "News" || boardid == "Gaming" ) {

    }
    else {
        $( "#invalidBoard" ).css( { "text-align" : "center" , "padding" : "20px" , "color" : "#ff0000" } );
    }

    $( ".pagination" ).on( "click" , function() {
        if ( search == "" ) {
            window.location.href = "./displayBoard.php?boardid=" + boardid + "&page=" + $( this ).data( "value" );
        }
        else {
            window.location.href = "./displayBoard.php?boardid=" + boardid + "&search=" + search + "&page=" + $( this ).data( "value" );
        }
    });

    // use pageid to dark the current page
    $( "#query input" ).attr( "value" , search );

    // hide search bar if no post
    var temp = <?php echo $totalPost; ?>;
    if ( temp <= 0 ) {
        $( "#query" ).parent().hide();
    }
</script>
