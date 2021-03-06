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
    protected $middleware;

    public function __construct($method, $url, $handler, array $params = null)
    {
        $this->method = strtoupper($method);
        $this->url = $url;
        $this->handler = $handler;

        $this->middleware = new \SplStack;

        if(!empty($params) && is_array($params)) {
            $this->setParams($params);
        }
    }

    public function setParams(array $params)
    {
        foreach ($params as $key => $param) {
            switch (strtolower($key)) {
                case "middleware":
                    $this->setMiddleware($param);
                    break;
                case "name":
                    $this->name = $param;
                    break;
            }
        }
    }


    public function middleware($middleware)
    {
        $this->setMiddleware($middleware);
    }

    /**
     * @param mixed|MiddlewareInterface|array  $middleware
     * @return $this
     */
    public function setMiddleware($middleware)
    {
        if(!is_array($middleware)) {
            $this->middleware[] = $middleware;
        } else {
           $this->middleware->push($middleware);
        }
        return $this;
    }

    public function middlewareExist()
    {
        return !$this->middleware->isEmpty();
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