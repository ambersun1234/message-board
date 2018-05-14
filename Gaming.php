<html lang="en">
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <title>看板列表</title>
        <style>
            *{
                box-sizing: border-box;
            }
            body{
                /*color: #FFFFF2;*/
                background: #8A8A00;
            }
            .footer{
                height: 40px;
                box-sizing: border-box;
                position: fixed;
                bottom: 0;
                width: 100%;
            }
            
        [class*="col-"] {
                width: 100%;
                float: left;
                padding: 15px;
                border: 1px;
                opacity:0.6;
            }
            
            @media screen and ( min-width:992px ){
                .col-md-1  {width: calc(100% * 1 / 12);}
                .col-md-2  {width: calc(100% * 2 / 12);}
                .col-md-3  {width: calc(100% * 3 / 12);}
                .col-md-4  {width: calc(100% * 4 / 12);}
                .col-md-5  {width: calc(100% * 5 / 12);}
                .col-md-6  {width: calc(100% * 6 / 12);}
                .col-md-7  {width: calc(100% * 7 / 12);}
                .col-md-8  {width: calc(100% * 8 / 12);}
                .col-md-9  {width: calc(100% * 9 / 12);}
                .col-md-10 {width: calc(100% * 10 / 12);}
                .col-md-11 {width: calc(100% * 11 / 12);}
                .col-md-12 {width: calc(100% * 12 / 12);}
            }
            @media screen and ( min-width:768px )and ( max-width:991px ){
                .col-sm-1  {width: calc(100% * 1 / 12);}
                .col-sm-2  {width: calc(100% * 2 / 12);}
                .col-sm-3  {width: calc(100% * 3 / 12);}
                .col-sm-4  {width: calc(100% * 4 / 12);}
                .col-sm-5  {width: calc(100% * 5 / 12);}
                .col-sm-6  {width: calc(100% * 6 / 12);}
                .col-sm-7  {width: calc(100% * 7 / 12);}
                .col-sm-8  {width: calc(100% * 8 / 12);}
                .col-sm-9  {width: calc(100% * 9 / 12);}
                .col-sm-10 {width: calc(100% * 10 / 12);}
                .col-sm-11 {width: calc(100% * 11 / 12);}
                .col-sm-12 {width: calc(100% * 12 / 12);}
        }
            .title
            {
                 width:100%;
                 height:60px;
                 padding-top:10px;
                 font-family:arial;
            }
            .button.absolute{
                background-color:#8A8A00;
                color: #FFFFF2;
                border-style: outset;
                padding: 15px 32px;
                text-align: center;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                cursor: pointer;
                position: absolute;
                right: 0;
            }
        </style>
        
    </head>
    <body>
      <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="container tool_bar">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php" style="font-size: 30px;">message-board</a><font size="1">></font>
                              
                <a class="navbar-brand" href="hotboard_preschool.php" style="font-size: 30px;"><font size = "3">看板></font><u>Gaming</u></a>

            </div>
            <div id="navbar" class="collapse navbar-collapse tool_bar">
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
                            echo '<li><a href="#">' . $_SESSION['user'] . '</a></li>';
                            echo '<li><a href="signOut.php">log out</a></li>';
                        }
                        else {
                            echo '<li><a href="signIn.php">登入</a></li>';
                            echo '<li><a href="/index.php#signUp">註冊</a></li>';
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    
    <div id=add_artical>
        <button type="button" class="absolute button" style="#8A8A00" onclick="location.href='add_artical.php'">新增文章</button>
    </div>
       <p style="color:#FFFFF2; font-size:60px;"> Test 你好 123 </p>
        <div class="footer">
            <p style="font-size: 20px; font-family: serif;">Copyright 2018 message-board.Inc. All rights reserved.</p>
        </div>
    </body>
</html>