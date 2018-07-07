<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 06.07.18
 * Time: 12:57
 */

namespace Src\Routing;

class RouteItem
{
    public $method;
    public $url;
    public $handler;
    public $name;

    public function __construct($method, $url, $handler, $name = null)
    {
        $this->method = strtoupper($method);
        $this->url = $url;
        $this->handler = $handler;
        $this->name = $name;
    }

    public function match() : bool
    {
        //Удаляем из url параметры если есть
        if(!empty($_SERVER['QUERY_STRING']))
            $url = substr(
                $_SERVER['REQUEST_URI'],0,
                strpos($_SERVER['REQUEST_URI'], $_SERVER['QUERY_STRING'])-1);
        else
            $url = $_SERVER['REQUEST_URI'];

        $method = $_SERVER['REQUEST_METHOD'];

        if($this->url == $url && $this->method == $method)
            return true;
        return false;
    }
}