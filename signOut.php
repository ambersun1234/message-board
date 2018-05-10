<?php
    session_start(); // allways on the first line of the code

    $_SESSION['loggedin'] = false; // set to "not" logged in
    $_SESSION['user'] = ""; // clear username
    header("Location: index.php"); // redirection to index.php
 ?>
