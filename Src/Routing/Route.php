<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 06.07.18
 * Time: 12:57
 */

namespace Src\Routing;

use Psr\Http\Message\RequestInterface;
use Src\Middleware\MiddlewareInterface;

class Route
{
    public $method;
    public $url;
    public $handler;
    public $name;
    protected $middleware = [];

    public function __construct($method, $url, $handler, $name = null)
    {
        $this->method = strtoupper($method);
        $this->url = $url;
        $this->handler = $handler;
        $this->name = $name;
    }

    public function middleware(MiddlewareInterface $middleware)
    {
        $this->middleware[] = $middleware;
        return $this;
    }

    public function middlewareExist()
    {
        return !empty($this->middleware);
    }

    public function getMiddleware()
    {
        return $this->middleware;
    }



    public function match(RequestInterface $request) : bool
    {
        return $this->url == $request->getUri()->getPath() && $this->method == $request->getMethod();
    }
}