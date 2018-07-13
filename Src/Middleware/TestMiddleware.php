<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 13.07.18
 * Time: 10:32
 */

namespace Src\Middleware;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TestMiddleware implements MiddlewareInterface
{

    public function handle(RequestInterface $request, ResponseInterface $response, $next)
    {
        // process before
        return $next($request, $response, $next);
        // process after
    }
}