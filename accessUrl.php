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


//get redirect user to url when requested