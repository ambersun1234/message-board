<html lang="en">
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="custom.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>-->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <title>add new post</title>

    </head>

    <body  style="background-color: #f9f9f9;">
        <?php include "statusColumn.php"; ?>

        <div class="add_artical">
            <form name="reg" method="post">
                <br>title:<br>
                <textarea name="title" rows="1" cols="1" maxlength="30"></textarea><br>

                article:<br>
                <textarea name="artical" style="color:black;"></textarea><br><br>

                <button type="submit" class="btn btn-default">Submit</button>
                <button type="reset" class="btn btn-default" onClick="reset()">Reset</button>
            </form>
        </div>
    </body>
</html>
