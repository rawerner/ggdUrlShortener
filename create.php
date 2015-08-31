<?php

require_once('includes/config.php');
require_once('includes/database.php');
require_once('includes/shortUrl.php');

// Create new connection to Database
$database = new GgdShortener_Database($config['database']);

// get request data
$path = substr($_SERVER['REQUEST_URI'], 1);
$httpMethod = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
$url = NULL;

// if url is /create and POST then create code and save to code and url database
if ($path == "create" && "POST" == $httpMethod)
{
    if (isset($_POST['url']))
    {
        $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
    }
    //create random code
    $code = sha1_file($url);
    $code = substr($code, 0, 5);

    // create new shortUrl object
    $shortUrl = new ShortUrl();
    $shortUrl->setUrl($url);
    $shortUrl->setCode($code);
    $database->insertNewUrl($shortUrl);
}