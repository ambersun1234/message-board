<?php
    session_start();

    $usernameOrEmail = getData( $con , $_POST["_usernameOrEmail"] );
    $deleteMyAccount = getData( $con , $_POST["_deleteMyAccount"] );
    $password = getData( $con , $_POST["_password"] );

    function getData( $con , $data ) { // prevent xss and sql injection
        $data = stripslashes( $data ); // remove all \
        $data = htmlspecialchars( $data ); // turn &"'<> to real entity
        $data = mysqli_real_escape_string( $con , $data );
        return $data;
    }
 ?>
