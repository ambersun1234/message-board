<?php
    session_start();

    include_once './connectToDB.php';

    $returnData = array();

    if ( isset( $_POST["userid"] ) && $_POST["userid"] != "" ) {
        $userid = $_POST["userid"];
    }
    if ( isset( $_POST["job"] ) && $_POST["job"] != "" ) {
        $job = $_POST["job"];
    }
    if ( isset( $_POST["username"] ) && $_POST["username"] != "" ) {
        $username = $_POST["username"];
    }
    //------------------------------------------------

    if ( $job == 1 ) {
        $sql = "UPDATE account SET image = '' WHERE userid = '" . $userid . "'";

        $query = mysqli_query( $con , $sql );
        if ( mysqli_error( $query ) ) {
            $returnData[ "code" ] = 1;
            $returnData[ "message" ] = "mysql error , please try again...";
        }
        else {
            $returnData[ "code" ] = 0;
            $returnData[ "message" ] = "";
        }
    }
    else {
        $sql = "SELECT image FROM account where username = '" . $username . "'";

        $query = mysqli_query( $con , $sql );
        if ( mysqli_error( $query ) ) {
            $returnData[ "code" ] = 1;
            $returnData[ "message" ] = "mysql error , please try again...";
        }
        else {
            $row = $query->fetch_assoc();
            $returnData[ "code" ] = 0;
            $returnData[ "message" ] = $row["image"];
        }
    }

    echo json_encode( $returnData );

    include_once './disconnectToDB.php';
 ?>
