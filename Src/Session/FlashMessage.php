<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 12.07.18
 * Time: 12:07
 */

namespace Src\Session;


class FlashMessage implements FlashMessageInterface
{
    public $type;
    public $title;
    public $body;

    public function __construct($type, $title, $body)
    {
        $this->title = $title;
        $this->body = $body;
        $this->setType($type);
    }

    public function setType($type)
    {
        switch ($type) {
            case "error":
                $this->type = "danger";
                break;
            case "warning":
                $this->type = "warning";
                break;
            case "info":
                $this->type = "info";
                break;
            case "success":
                $this->type = "success";
                break;
        }
    }

    public function getType()
    {
        return $this->type;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getBody()
    {
        return $this->body;
    }

}