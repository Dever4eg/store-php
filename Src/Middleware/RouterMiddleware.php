<?php
/**
 * Created by PhpStorm.
 * User: dever
 * Date: 22.07.18
 * Time: 0:11
 */

namespace Src\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Src\App;

class RouterMiddleware implements MiddlewareInterface
{

    public function handle(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $match = App::getRouter()->getMatch($request);

        if($match->middlewareExist()) {
            foreach ($match->getMiddleware() as $item) {
                App::getMiddleware()->register($item);
            }
        }

        return $next($request, $response, $next);
    }

}