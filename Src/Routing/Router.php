<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 06.07.18
 * Time: 12:57
 */

namespace Src\Routing;

use Psr\Http\Message\RequestInterface;
use Src\App\AppSingleComponent;
use Src\Exceptions\Http\Error404Exception;

class Router implements AppSingleComponent
{
    public function __construct()
    {
    }

    protected $routes = [];
    /**
     * @var Route
     */
    protected $match = null;

    public function add($method, $url, $handler, $name = null)
    {
        return $this->routes[] = new Route($method, $url, $handler, $name);
    }

    public function get($url, $handler, $name = null)
    {
        return $this->add('get', $url, $handler, $name);
    }

    public function post($url, $handler, $name = null)
    {
        return $this->add('post', $url, $handler, $name);
    }

    public function put($url, $handler, $name = null)
    {
        return $this->add('put', $url, $handler, $name);
    }

    public function patch($url, $handler, $name = null)
    {
        return $this->add('patch', $url, $handler, $name);
    }

    public function delete($url, $handler, $name = null)
    {
        return $this->add('delete', $url, $handler, $name);
    }

    /**
     * @return Route[]
     */
    public function getAll() : array
    {
        return $this->routes;
    }

    /**
     * @param RequestInterface $request
     * @return Route
     * @throws Error404Exception
     */
    public function getMatch(RequestInterface $request)
    {
        if(!empty($this->match)) {
            return $this->match;
        }

        foreach ($this->getAll() as $route) {
            if ($route->match($request)) {
                return ($this->match = $route);
            }
        }

        throw new Error404Exception();
    }
}