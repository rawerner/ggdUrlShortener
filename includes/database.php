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
            $DSN = 'mysql:dbname' . $dbInfo['database_name'] . ';host=' . $dbInfo['host'] . (!empty($dbInfo['port']) ? ':' . $dbInfo['port'] : '');
            parent::__construct($DSN, $dbInfo['username'], $dbInfo['password'], $options);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "I'm sorry, Dave. I'm afraid I can't do that.";
            echo $e->getMessage();
        }
        //Just to check if database connection exists (temporary)
        echo @mysql_ping() ? 'true' : 'false';
    }

    // Insert new Url and Code into DB
    public function insertNewUrl($shortUrl)
    {
        $sql = "INSERT INTO short_url (`url`,`code`) VALUES (:url,:code)";
        $url = $shortUrl->url;
        $code = $shortUrl->code;

        try {
            if ($shortUrl->isValid()) {
                $this->beginTransaction();
                $stmt = $this->prepare($sql);
                $stmt->bindValue(':url', $url, PDO::PARAM_STR);
                $stmt->bindValue(':code', $code, PDO::PARAM_STR);
                $stmt->execute();
                $newId = $this->lastInsertId();
                $this->commit();
                $shortUrl->setId($newId);
                return $newId;
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log("An error occured while binding data");
            error_log($e->getMessage());
            $this->rollBack();
            return false;
        }
    }

    public function retrieveOriginalUrl($code)
    {
        //Get url with code and return
    }
}