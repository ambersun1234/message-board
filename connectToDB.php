<?php
    $servername = "localhost";
    $loginname = "root";
    $loginpassword = "1234";
    $dbname = "message_db";

    $con = mysqli_connect( $servername , $loginname , $loginpassword , $dbname );
    if ( !$con ) die( "connection failed: " . mysqli_connect_error() );
    $con->set_charset("utf8");
?>
