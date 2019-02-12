<?php session_start(); ?>

<script type="text/javascript">
    $( document ).ready( function () {
        var url = window.location;
        $( 'ul.nav a[href="' + url + '"]' ).parent().addClass( 'active' );
        $( 'ul.nav a' ).filter( function() {
             return this.href == url;
        }).parent().addClass( 'active' );
    });

    $( document ).ready( function() {
        $.post( "./picture.php" , { username : '<?php echo $_SESSION['user']; ?>' , job : 2 } , function( data ) {
            if ( data.code == 0 ) {
                var image = data.message == "" ? "default.jpeg" : data.message;
                $( "#ajaxPicture" ).attr( "src" , "images/" + image );
            }
        } , "json" ).fail( function() {

        });
    });
</script>

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
                <li><a href="/index.php#board">BOARD</a></li> <!--jump to all board-->
            </ul>
            <ul class="nav navbar-nav navbar-left">
                <li><a href="/displayBoard.php?boardid=Gaming&page=1">Gaming board</a></li> <!--jump to Gaming board-->
            </ul>
            <ul class="nav navbar-nav navbar-left">
                <li><a href="/displayBoard.php?boardid=News&page=1">News board</a></li> <!--jump to New board-->
            </ul>
            <ul class="nav navbar-nav navbar-left">
                <li><a href="/displayBoard.php?boardid=Gossip&page=1">Gossip board</a></li> <!--jump to Gossip board-->
            </ul>
                <link rel="stylesheet" type="text/css" href="custom.css">
                <?php
                /*
                 *  check whether login or not
                 *  if not login yet --> show 登入( signIn.php ) 註冊( signUp.php )
                 *  else --> show username and logout( signOut.php )
                 *  $_SESSION has two variables: loggedin & user
                 *  if user logged in --> $_SESSION['loggedin'] = true & $_SESSION['user'] = $username
                 */
                 ?>
                <?php
                if ( isset( $_SESSION['loggedin'] ) && $_SESSION['loggedin'] == true ) {
                 ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="signOut.php">log out</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="/accountCenter.php"><?php echo $_SESSION['user']; ?></a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="/accountCenter.php"><img id="ajaxPicture" src="images/default.jpeg" alt="Profile picture" height="30" weight="25" align="middle"></a>
                    </ul>
                <?php
                }
                else {
                 ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="/index.php#signUp">Sign up</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="signIn.php">Sign in</a></li>
                    </ul>
                <?php
                }
                 ?>
        </div>
    </div>
</div>
