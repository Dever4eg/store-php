<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 06.07.18
 * Time: 12:57
 */

namespace Src\Routing;

use http\Exception;
use Src\Traits\Singleton;

class RouteHandler
{
    use Singleton;

    protected $routes;
    protected $match = null;

    public function add($method, $url, $handler, $name = null)
    {
        $this->routes[] = new RouteItem($method, $url, $handler, $name);
    }

    public function getAll()
    {
        return $this->routes;
    }

    public function getMatch()
    {
        if(!empty($this->match))
            return $this->match;

        //Удаляем из url параметры если есть
        if(!empty($_SERVER['QUERY_STRING']))
            $url = substr(
                $_SERVER['REQUEST_URI'],0,
                strpos($_SERVER['REQUEST_URI'], $_SERVER['QUERY_STRING'])-1);
        else
            $url = $_SERVER['REQUEST_URI'];

        foreach ($this->routes as $route) {
            if($route->url == $url) {
                $this->match = $route;
                return $this->match;
            }
        }
        // TODO: 404 error
        die('404 error');
    }


    public function getHandler()
    {
        return $this->getMatch()->handler;
    }
}