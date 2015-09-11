<?php

require_once('abstract/DTO_Abstract.php');

class ShortUrlDTO extends DTO_Abstract
{
    protected static $options = array(
        'validations' => array(
            'code' => array(
                'maxlength' => 5,
                'notNull' => false
            ),
            'url' => array(
                'maxlength' => 2083,
                'notNull' => false
            )
        )
    );

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