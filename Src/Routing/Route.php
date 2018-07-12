<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 06.07.18
 * Time: 12:57
 */

namespace Src\Routing;

use Psr\Http\Message\RequestInterface;

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

    public function match(RequestInterface $request) : bool
    {
        return $this->url == $request->getUri()->getPath() && $this->method == $request->getMethod();
    }
}