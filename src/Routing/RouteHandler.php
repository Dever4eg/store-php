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

    public function match()
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


    public function handle()
    {
        if(empty($this->match))
            $this->match = $this->match();

        $handler = $this->match->handler;

        if(is_callable($handler)) {
            return $handler();
        } elseif (is_string($handler)) {
            $arr = explode('@', $handler);

            $controller_name = "\\App\\Controllers\\" . $arr[0];
            $action_name = $arr[1];

            if(class_exists($controller_name))
                $controller = new $controller_name;
            if(method_exists($controller, $action_name))
                return $controller->$action_name();
        }
        // TODO: Error unknown paramets
        die('error parse handler');
    }
}