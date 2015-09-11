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
if ($path == "create" && "POST" == $httpMethod) {
    if (isset($_POST['url'])) {
        $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
    }
    //create random code
    $code = sha1_file($url);
    $code = substr($code, 0, 5);

    // create new shortUrl object
    $shortUrl = new ShortUrlDTO();
    $shortUrl->setUrl($url);
    $shortUrl->setCode($code);;

    $sql = "INSERT INTO short_url (`url`,`code`) VALUES (:url,:code)";
    try {
        $database->beginTransaction();
        $stmt = $database->prepare($sql);
        $stmt->bindValue(':url', $shortUrl->url, PDO::PARAM_STR);
        $stmt->bindValue(':code', $shortUrl->code, PDO::PARAM_STR);
        $stmt->execute();
        $newId = $database->lastInsertId();
        $database->commit();
        $shortUrl->setId($newId);
    } catch (Exception $e) {
        error_log("An error occured while binding data");
        error_log($e->getMessage());
        $database->rollBack();
        return false;
    }

    $response = array();
    $newShortUrl = 'http://ggd.ly/' . $shortUrl->code;
    return $newShortUrl;
//    if (!is_null($shortUrl->id)){
//       echo '<div class="copyUrl">' . $newShortUrl . '</div>';
//    }

}