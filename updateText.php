<?php
    if ( isset( $_POST["type"] ) && $_POST["type"] != "" ) {
        $type = htmlspecialchars( $_POST["type"] );
    }
    if ( isset( $_POST["id"] ) && $_POST["id"] != "" ) {
        $id = htmlspecialchars( $_POST["id"] );
    }
    if ( isset( $_POST["text"] ) && $_POST["text"] != "" ) {
        $text = htmlspecialchars( $_POST["text" ] );
    }

    if ( $type == "post" && strlen( $text ) < 10 ) {
        $returnData[ "code" ] = 1;
        $returnData[ "text" ] = "< 10 word";
        echo json_encode( $returnData );
    }

    $returnData = array();

    include_once "./connectToDB.php";

    $sql = "UPDATE " . $type . " SET article = \"" . $text . "\" WHERE " . $type . "id = \"" . $id . "\"";

    $query = mysqli_query( $con , $sql );
    if ( !$query ) {
        $returnData[ "code" ] = 1;
        $returnData[ "text" ] = "update failed";
    }
    else {
        $returnData[ "code" ] = 0;
        $returnData[ "text" ] = $text;
    }

    include "./disconnectToDB.php";

    echo json_encode( $returnData );

 ?>
