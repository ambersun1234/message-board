<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container tool_bar">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php" style="font-size: 30px;">message-board</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse tool_bar">
            <ul class="nav navbar-nav navbar-left">
                <li><a href="/index.php#board">BOARD</a></li> <!--jump to 所有看板-->
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                    // include css file . note: css can not write in php echo!!
                    echo '<link rel="stylesheet" type="text/css" href="custom.css">';

                    /*
                     *  check whether login or not
                     *  if not login yet --> show 登入( signIn.php ) 註冊( signUp.php )
                     *  else --> show username and logout( signOut.php )
                     *  $_SESSION has two variables: loggedin & user
                     *  if user logged in --> $_SESSION['loggedin'] = true & $_SESSION['user'] = $username
                     */
                    if ( isset( $_SESSION['loggedin'] ) && $_SESSION['loggedin'] == true ) {
                        echo '<li><a href="/accountCenter.php">' . $_SESSION['user'] . '</a></li>';
                        echo '<li><a href="signOut.php">log out</a></li>';
                    }
                    else {
                        echo '<li><a href="signIn.php">Sign in</a></li>';
                        echo '<li><a href="/index.php#signUp">Sign up</a></li>';
                    }
                ?>
            </ul>
        </div>
    </div>
</div>
