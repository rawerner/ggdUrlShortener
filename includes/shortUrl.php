<?php

require_once('abstract/DTO_Abstract.php');

class ShortUrl extends DTO_Abstract
{
    public $id;

    public $url;

    public $code;

    function setId($id)
    {
        $this->id = $id;
    }

    function getId()
    {
        return $this->id;
    }

    function setUrl($url)
    {
        $this->url = $url;
    }

    function getUrl()
    {
        return $this->url;
    }

    function setCode($code)
    {
        $this->code = $code;
    }

    function getCode()
    {
        return $this->code;
    }
}