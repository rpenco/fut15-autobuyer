<!DOCTYPE h2 PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/FUT15AB/src/app/theme/style.css" />
</head>

    <body>
        <h2>FIFA ULTIMATE TEAM 15 : AUTO BUYER!</h2>

        <a href="/FUT15AB/market" class="button" >Autobuyer</a>
        <a href="/FUT15AB/players" class="button">Liste des joueurs</a>
        <a href="/FUT15AB/config" class="button log-info" >Paramètres</a>


        <br/>
        <div class="scroll">
        <?php

        $split = @split('/', $_SERVER['REQUEST_URI']);

        $path = __DIR__.'/'.$split[2].'.php';
        if (file_exists($path)) {
            require_once $path;
        }
        ?>
        </div>
    </body>
</html>
