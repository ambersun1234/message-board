<?php
    $servername = "localhost";
    $loginname = "root";
    $loginpassworld = "1234";
    $dbname = "messageDB";

    $con = mysqli_connect( $servername , $loginname , $loginpassworld , $dbname );
    if ( !con ) die( "connection failed: " . mysqli_connect_error() );
?>
