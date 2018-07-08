<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 06.07.18
 * Time: 12:57
 */

namespace Src\Routing;

use Src\App\AppSingleComponent;

class Router implements AppSingleComponent
{
    public function __construct()
    {
    }

    protected $routes = [];
    protected $match = null;

    public function add($method, $url, $handler, $name = null)
    {
        $this->routes[] = new Route($method, $url, $handler, $name);
    }

    /**
     * @return Route[]
     */
    public function getAll() : array
    {
        return $this->routes;
    }

    public function getMatch()
    {
        if(!empty($this->match))
            return $this->match;

        foreach ($this->getAll() as &$route)
            if($route->match())
                return ($this->match = $route);

        // TODO: 404 error
        die('404 error');
    }


    public function getHandler()
    {
        return $this->getMatch()->handler;
    }
}