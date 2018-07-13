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

    public function __construct()
    {
        $this->queue = new \SplQueue();
    }

    public function register(MiddlewareInterface $middleware)
    {
        $this->queue->enqueue($middleware);
    }

    public function run(RequestInterface $request, callable $handler)
    {
        $this->handler = $handler;
        return $this->nextHandler($request, new Response(), [$this, 'nextHandler']);
    }


    public function nextHandler(RequestInterface $request, ResponseInterface $response, $next)
    {
        if($this->queue->isEmpty()) {
            return ($this->handler)($request, $response);
        }
        return $this->queue->dequeue()->handle($request, $response, $next);
    }
}