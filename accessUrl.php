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

// if url has letters and digits, has a length of 5 and is GET then execute
if ((ctype_alnum($path)) && (strlen($path) == 5) && ("GET" == $httpMethod)) {

    $sql = "SELECT * FROM short_url WHERE code = :code";

    try {
        $database->beginTransaction();
        $q = $database->prepare($sql);
        $q->bindValue(':code', $path, PDO::PARAM_STR);
        $q->execute();
    } catch (Exception $e) {
        error_log("An error occured while getting data");
        error_log($e->getMessage());
        $database->rollBack();
        return false;
    }

    $result = $q->fetch();
    if($q->rowCount() == 1) {
        header('Location: ' . $result['url'], true, 303);
        exit();
    }
}