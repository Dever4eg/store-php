<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 13.07.18
 * Time: 10:32
 */

namespace Src\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TestMiddleware implements MiddlewareInterface
{

    public function handle(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        // process before
        return $next($request, $response, $next);
        // process after
    }
}