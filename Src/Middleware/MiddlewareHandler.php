<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 13.07.18
 * Time: 10:15
 */

namespace Src\Middleware;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Src\App\AppSingleComponent;
use Zend\Diactoros\Response;

class MiddlewareHandler implements AppSingleComponent
{
    protected $queue = [];
    protected $handler;
    protected $aliases = [];

    public function __construct()
    {
        $this->queue = new \SplQueue();
    }

    /**
     * @param mixed|MiddlewareInterface|string $middleware
     * @return bool
     */
    public function register($middleware)
    {
        if($middleware instanceof MiddlewareInterface) {
            $this->queue->enqueue($middleware);
        } elseif(is_string($middleware) && isset($this->aliases[strtolower($middleware)])) {
            $this->queue->enqueue($this->aliases[strtolower($middleware)]);
        } else {
            return false;
        }
        return true;
    }

    public function run(RequestInterface $request, callable $handler)
    {
        $this->handler = $handler;
        return $this->nextHandler($request, new Response(), [$this, 'nextHandler']);
    }

    public function setAliases($aliases)
    {
        $this->aliases = array_merge($this->aliases, $aliases);
    }

    public function setAlias($alias, $class)
    {
        $this->aliases[strtolower($alias)] = $class;
    }


    public function nextHandler(RequestInterface $request, ResponseInterface $response, $next)
    {
        if($this->queue->isEmpty()) {
            return ($this->handler)($request, $response);
        }
        return $this->queue->dequeue()->handle($request, $response, $next);
    }
}