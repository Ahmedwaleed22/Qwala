<?php
include 'includes/init.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="<?php echo $css . 'quiz.css' ?>" />
    </head>
    <body>
        <div class="pointscontainer">
            <div class="truesign">&check;</div>
            <div class="congrats">Congratulations!</div>
            <div class="title"></div>
            <span class="points">Your Scored Points: <?php echo $_GET['score'] ?> / <?php echo $_GET['total'] ?></span>
            <div class="actions">
                <button onclick="window.open('/todolist', '_self')">Go To Home Page</button>
            </div>
        </div>
    </body>
</html>