<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 06.07.18
 * Time: 12:57
 */

namespace Src\Routing;

class Route
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
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        if($this->url == $url && $this->method == $method)
            return true;
        return false;
    }
}