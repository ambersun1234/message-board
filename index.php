<?php session_start() ?>

<!DOCTYPE html>

<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>message-board</title>

    <!--this is google arvo fonts and font awesome-->
    <link href="https://fonts.googleapis.com/css?family=Arvo" rel="stylesheet">
    <link rel="stylesheet" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="custom.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    </head>

    <body>
        <?php include "statusColumn.php";?>

        <?php // check signUp valid or not and insert into database
            $usernameErr = $passwordErr = $emailErr = $createAccountErr = ""; // initialize
            $username = $email = $password = ""; // initialize
            $createAccountSuc = ""; // initialize

            if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset( $_POST["SUBMIT_signup"] ) ) { // active when submit
                include "connectToDB.php";

                $username = getData( $con , $_POST["_username"] );
                $email = getData( $con , $_POST["_email"] );
                $password = getData( $con , $_POST['_password'] );

                checkUsername( $con , $username );
                checkEmail( $con , $email );
                checkPassword( $con , $password );

                if ( checkValid() ) {
                    if ( insertAccountInfo( $con , $username , $email , $password ) ) {
                        if ( isset( $_SESSION['user'] ) && $_SESSION['loggedin'] == true ); // already logged in
                        else { // not loggin yet
                            // auto log in when signUp success
                            $_SESSION['loggedin'] = true;
                            $_SESSION['user'] = $username;
                            header("Location: index.php"); // redirection
                        }
                    }
                }
                include "disconnectToDB.php";
            }
            function getData( $con , $data ) { // prevent xss and sql injection
                $data = stripslashes( $data ); // remove all \
                $data = htmlspecialchars( $data ); // turn &"'<> to real entity
                $data = mysqli_real_escape_string( $con , $data );
                return $data;
            }
            function insertAccountInfo( $con , $username , $email , $password ) {
                $query = mysqli_query( $con , "select max( userid ) from account" ); // select newest user id
                $row = $query->fetch_assoc(); // get data;
                $id = $row["max( userid )"];
                $password = password_hash( $password , PASSWORD_DEFAULT ); // encrypt
                $id++;

                $sql = "insert into account values( '" . $id . "' , '" . $username . "' , '" . $password . "' , '" . $email . "' , '' )";
                $query = mysqli_query( $con , $sql );
                if ( $query ) {
                    $GLOBALS["createAccountSuc"] = "Account create successfully!!<br>";
                    return true;
                }
                else {
                    $GLOBALS["createAccountErr"] = "Create account failed , please try again.<br>";
                    return false;
                }
            }
            function checkUsername( $con , $username ) {
                if ( $username == "" ) $GLOBALS['usernameErr'] = "Username can not be blank!!<br>";
                else {
                    $sql = "select username from account where username = '" . $username . "'";
                    $query = mysqli_query( $con , $sql );
                    $result = $query->num_rows;

                    if ( $result > 0 ) $GLOBALS['usernameErr'] = "Username already taken!!<br>";
                }
            }
            function checkEmail( $con , $email ) {
                if ( $email == "" ) $GLOBALS['emailErr'] = "Email can not be blank!!<br>";
                else if ( !filter_var( $email , FILTER_VALIDATE_EMAIL ) ) $GLOBALS['emailErr'] = "Invalid email format!!<br>";
                else {
                    $sql = "select email from account where email = '" . $email . "'";
                    $query = mysqli_query( $con , $sql );
                    $result = $query->num_rows;
                    if ( $result > 0 ) $GLOBALS['emailErr'] = "Email already taken!!<br>";
                }
            }
            function checkPassword( $con , $pasw ) {
                if ( $pasw == "" ) {
                    $GLOBALS['passwordErr'] = "Password can not be blank!!<br>";
                    return;
                }
                if ( strlen( $pasw ) <= 7 ) { // check at least 8 characters
                    $GLOBALS['passwordErr'] = "Must at least 8 characters!!<br>";
                    return;
                }
                if ( ( !preg_match( '/[A-Z]/' , $pasw ) || !preg_match( '/[a-z]/' , $pasw ) ) && !preg_match( '/[0-9]/', $pasw ) ) {
                    $GLOBALS['passwordErr'] = "Must contain one letter and one digit!!<br>";
                    return;
                }
            }
            function checkValid() {
                if ( $GLOBALS['usernameErr'] == false && $GLOBALS['passwordErr'] == false && $GLOBALS['emailErr'] == false ) return true;
                else return false;
            }
         ?>

        <div class="jumbotron" id="signUp">
            <div class="container">
                <div class="row">
                    <div class="col-xs-6" style="text-align: left;">
                        <h2>Build for everyone!!<br></h2>
                        <h3>Our goal is to create a platform for everyone to talk with.<br> You can talk to people around the world by using <strong>message-board</strong>.<br> Sign up or Sign in to enjoy our service!!.</h3>
                        <br><br><br>
                    </div>
                    <div class="col-xs-6 signUp">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <span >Username</span><br>
                            <input type="text" name="_username" placeholder="Pick a username" value="<?php echo $username; ?>"><br>
                            <?php if ( $usernameErr != "" ) echo "<div class='invalid'>" . $usernameErr . "</div>"; ?> <!--username need to be unique-->

                            <span>Email</span><br>
                            <input type="text" name="_email" placeholder="you@example.com" value="<?php echo $email; ?>"><br>
                            <?php if ( $emailErr != "" ) echo "<div class='invalid'>" . $emailErr . "</div>"; ?> <!--email need to be unique-->

                            <span>Password</span><br>
                            <input type="password" name="_password" placeholder="Create a password"><br>
                            <div class="passwordWarn">use at least one letter , one numeral and six characters</div>
                            <?php if ( $passwordErr != "" ) echo "<div class='invalid'>" . $passwordErr . "</div>"; ?> <!--password need to be valid-->

                            <?php
                                if ( $createAccountErr != "" ) echo "<div class='invalid'>" . $createAccountErr . "</div>";
                                else if ( $createAccountSuc != "" ) echo "<div class='valid'>" . $createAccountSuc . "</div>";
                             ?>

                            <button type="submit" class="btn btn-default" name="SUBMIT_signup">Sign up for message-board</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="who-we-are">
            <div class="container homepage">
                <h2>Who We Are</h2>
                <hr>
                <p>We are <span style="color: #e74c3c">message-board</span>. We aim to improve the speed of information exchange, and the quality of information.</p>
            </div>
        </div>

        <div id="board">
            <div class="container homepage">
                <h2>board</h2>
                <hr>
                <!-- Example row of columns -->
                <div class="row">
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                        </span>
                        <h3><span class="head_emphasis">gaming</span></h3>
                        <!--<span class="emphasis">-->
                        <p>Want to know the <span class="emphasis">hottest game</span> right now?<br>Can't solve level 99 challenge?<br>Click the button below and we'll help you get everything you want.</p>
                        <p><a class="btn btn-default" href="Gaming.php" role="button" style="color: #e74c3c">Start Gaming &raquo;</a></p>
                    </div>
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                        </span>
                        <h3><span class="head_emphasis">news</span></h3>
                        <p>We'll giving you the latest news.<br>You can get the newest information around the world by simply checking our <span class="emphasis">NEWS board</span>. </p>
                        <p><a class="btn btn-default" href="#" target="_blank" role="button" style="color: #e74c3c">Check latest news &raquo;</a></p>
                    </div>
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                        </span>
                        <h3><span style="color: red;">gossip</span></h3>
                        <p>Want to know what people are disgusting about?<br>Don't want to miss the trend<br>Catch up with the <span class="emphasis">pop culture</span> just by clicking the button below!</p>
                        <p><a class="btn btn-default" href="#" target="_blank" role="button" style="color: #e74c3c">Start chatting &raquo;</a></p>
                    </div>
                </div>
            </div>
        </div>

    <footer>
        <div class="container">
            <p style="font-size: 30px; font-family: serif;">Copyright 2018 message-board.Inc. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap core JavaScript================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
    </body>
</html>
