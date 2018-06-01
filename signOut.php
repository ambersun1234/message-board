<?php
    session_start(); // allways on the first line of the code

    session_unset();
    header("Location: index.php"); // redirection to index.php
 ?>
