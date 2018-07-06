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
        $this->method = $method;
        $this->url = $url;
        $this->handler = $handler;
        $this->name = $name;
    }
}