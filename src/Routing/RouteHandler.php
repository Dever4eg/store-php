<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 06.07.18
 * Time: 12:57
 */

namespace Src\Routing;

use Src\Traits\Singleton;

class RouteHandler
{
    use Singleton;

    protected $routes;

    public function add($method, $url, $handler, $name = null)
    {
        $this->routes[] = new RouteItem($method, $url, $handler, $name);
    }

    public function getAll()
    {
        return $this->routes;
    }
}