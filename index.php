<?php
require_once('create.php');
require_once('accessUrl.php');
?>

<!Doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Girl Geek Dinner | Url Shortener</title>
    <link rel="stylesheet" type="text/css" href="/semantic/dist/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script type="application/javascript" src="js/jquery-2.1.4.min.js"></script>
    <script type="application/javascript" src="js/main.js"></script>
    <script src="semantic/dist/semantic.min.js"></script>
    <script type="text/javascript" src="ZeroClipboard.js"></script>
</head>
<body>
<div class="ui raised very padded text container segment ggd-container">
    <div class="ui huge header center aligned purple ggd-title">Girl Geek Dinner <br/>Url Shortener</div>
    <div class="one fields">
        <form class="ui center aligned form" action="/create" method="post">
            <div class="field">
                <label>Paste URL:</label>
                <input placeholder="http://domain.com" type="text" name="url">
            </div>
            <input id="submit" class="ui pink huge button submit" type="submit">
        </form>
    </div>
    <div class="copyUrl"><?php  echo $newShortUrl ?></div> <button id="click-to-copy">Click To Copy</button>
</div>
</body>
</html>

