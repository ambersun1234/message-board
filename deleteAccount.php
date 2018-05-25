<?php
    session_start();
    /*  session variables
     *
     *  session -- delete
     *  session -- deleteUsernameOrAccount
     *  session -- deleteDeleteMyAccount
     *  session -- loggedin
     *  session -- user
     */

     if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset( $_POST["submit"] ) && $_SESSION["loggedin"] == true ) { // active when submit
        include "connectToDB.php";

        $check = false;

        $usernameOrEmail = $_SESSION["deleteUsernameOrAccount"] = getData( $con , $_POST["_usernameOrEmail"] );
        $deleteMyAccount = $_SESSION["deleteDeleteMyAccount"] = getData( $con , $_POST["_deleteMyAccount"] );
        $password = getData( $con , $_POST["_password"] );

        $sql = "select * from account where ( username = '" . $usernameOrEmail . "' or email = '" . $usernameOrEmail . "' ) and username = '" . $_SESSION['user'] . "'";
        $query = mysqli_query( $con , $sql );
        $row = $query->fetch_assoc();
        $id = $row["userid"];
        $result = $query->num_rows;

        if ( $result < 1 ) {// username or email not found in database
            $_SESSION["delete"] = "Delete failed!!<br>"; // set error message
        }
        else { // account found
            if ( $deleteMyAccount != "delete my account" ) { // check "delete my account"
                $_SESSION["delete"] = "Delete failed!!<br>";
            }
            else {
                if ( !password_verify( $password , $row["password"] ) ) { // check password
                    $_SESSION["delete"] = "Delete account failed!!<br>";
                }
                else {
                    $_SESSION["delete"] = "success<br>";
                    $check = true;
                }
            }
        }
        header("Location: accountCenter.php");
        if ( !$check ) header("Location: accountCenter.php"); // redirect to accountCenter.php
        else deleteAccount( $con , $id );

        include "disconnectToDB.php";
    }
    else header("Location: index.php"); // redirect to index.php

    function deleteAccount( $con , $id ) {
        $sql = "delete from command where userid = '" . $id . "'"; // delete command sql
        $query = mysqli_query( $con , $sql );
        if ( $query ) { // delete command success
            $sql = "delete from post where userid = '" . $id . "'";
            $query = mysqli_query( $con , $sql );
            if ( $query ) { // delete post success
                $sql = "delete from account where userid = '" . $id . "'";
                $query = mysqli_query( $con , $sql );
                if ( !$query ) $_SESSION["delete"] = "Something went wrong , please submit again...3<br>";
            }
            else $_SESSION["delete"] = "Something went wrong , please submit again...2<br>";
        }
        else $_SESSION["delete"] = "Something went wrong , please submit again...1<br>";

        // check $_SESSION["delete"] status , decide redirection
        if ( $_SESSION["delete"] == "" ) {
            session_unset(); // clear all session
            header("Location: index.php");
        }
        else header("Location: accountCenter.php");
    }

    function getData( $con , $data ) { // prevent xss and sql injection
        $data = stripslashes( $data ); // remove all \
        $data = htmlspecialchars( $data ); // turn &"'<> to real entity
        $data = mysqli_real_escape_string( $con , $data );
        return $data;
    }
 ?>
