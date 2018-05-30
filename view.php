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

        <!-- show text from drop down using jquery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <script>
            $(function(){
                $( '#selectedMenu' ).on( 'change' , function() {
                    var url = $(this).val(); // get selected value
                    if ( url ) {
                        window.location = url;
                    }
                    else return false;
                });
            });
        </script>

        <title><?php echo $_GET["which"]; ?></title>

    </head>

    <body style="background-color: #f9f9f9;">
        <?php include "statusColumn.php"; ?>

        <!-- prevent direct url access -->
        <?php if ( $_SESSION["loggedin"] == false ) header("Location: index.php"); ?>

        <br><br>
        <div class="displayPost">
            <?php
                include "connectToDB.php";

                $id = getData( $con , $_GET["id"] );
                if ( !checkUserMatch( $id , $con ) ) echo "check failed.<br>"; //header("Location: index.php");
                $which = getData( $con , $_GET["which"] );
                $sort = getData( $con , $_GET["sort"] );

                echo "sort by <span style='color: #ff6060;'>";
                switch ( $sort ) {
                    case 'apt':
                        echo "Article post time<br>";
                        break;

                    case 'at':
                        echo "Article title<br>";
                        break;

                    case 'b':
                        echo "Board<br>";
                        break;

                    case 'ap':
                        echo "Article publisher<br>";
                        break;
                }

                echo "</span>or choose sort option: ";

                echo "<select class='form-control' id='selectedMenu' style='width: 14%; margin: 0px;'>"; // javascript stuff
                        echo "<option selected='selected'>Choose...</option>";
                        echo "<option value='/view.php?id=" . $id . "&which=" . $which . "&sort=ap" . "'>Article publisher</option>";
                        echo "<option value='/view.php?id=" . $id . "&which=" . $which . "&sort=b" . "'>Board</option>";
                        echo "<option value='/view.php?id=" . $id . "&which=" . $which . "&sort=at" . "'>Article title</option>";
                        echo "<option value='/view.php?id=" . $id . "&which=" . $which . "&sort=apt" . "'>Article post time</option>";
                echo "</select>";

                if ( $which == "post" ) $sql = "select a.username , p.postid , p.title , p.date_time as time , p.boardid from post p , account a where p.userid = " . $id . " and p.userid = a.userid";
                else {
                    $sql = "select c.text , c.date_time as time , c.postid , p.title , p.boardid , a.username from comment c , post p , account a ";
                    $sql .= "where c.postid = p.postid and c.userid = " . $id . " and p.userid = a.userid";
                }

                if ( checkSort( $sort ) ) { // if sort has assigned , add sql select condition
                    $sql .= " order by ";
                    switch ( $sort ) {
                        case "ap":
                            $sql .= "a.username desc";
                            break;

                        case "b":
                            $sql .= "p.boardid desc";
                            break;

                        case "at":
                            $sql .= "p.title desc";
                            break;

                        case "apt":
                            $sql .= "time desc";
                            break;
                    }
                }

                $query = mysqli_query( $con , $sql );
                if ( $query ) { // query success
                    if ( $query->num_rows > 0 ) {
                        while ( $row = $query->fetch_assoc() ) {
                            echo "<div class='postview'>";
                                echo '<a href="/displayPost.php?var1=' . $row['postid'] . '&title=' . $row['title'] . '">' . $row['title'] . '</a> ( ' . $row["boardid"] . ' )<br>';

                                echo "<p style='text-align: left;'>"; // same line but left
                                    echo $row["username"];
                                echo "<span style='float: right;'>"; // same line but right
                                    echo $row["time"];
                                echo "</span></p>";
                            echo "</div>";
                        }
                    }
                    else echo "You haven't " . $which . " anything!!<br>";
                }
                else echo "Something went wrong , please reload the page and try again...<br>";

                function getData( $con , $data ) {
                    $data = stripslashes( $data ); // remove all \
                    $data = htmlspecialchars( $data ); // turn &"'<> to real entity
                    $data = mysqli_real_escape_string( $con , $data );
                    return $data;
                }
                function checkSort( $sort ) {
                    if ( $sort == "ap" || $sort == "b" || $sort == "at" || $sort == "apt" ) return true;
                    else return false;
                }
                function checkUserMatch( $id , $con ) {
                    $sql = "select username from account where userid = " . $id . " and username = '" . $_SESSION["user"] . "'";
                    $query = mysqli_query( $con , $sql );
                    if ( $query->num_rows == 0 ) return false;
                    else return true;
                }

                include "disconnectToDB.php";
             ?>
        </div>
    </body>
</html>
