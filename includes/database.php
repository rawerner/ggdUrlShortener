<?php

class GgdShortener_Database extends \PDO
{

    public function __construct($dbInfo)
    {
        //Creating instance of database connection
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        try {
            $DSN = 'mysql:host=' . $dbInfo['host'] . ';dbname=' . $dbInfo['database_name'] . (!empty($dbInfo['port']) ? ':' . $dbInfo['port'] : '');
            parent::__construct($DSN, $dbInfo['username'], $dbInfo['password'], $options);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "I'm sorry, Dave. I'm afraid I can't do that.";
            echo "Connection: " . $e->getMessage();
        }
        //Just to check if database connection exists (temporary)
//        echo @mysql_ping() ? 'true' : 'false';
    }
}

