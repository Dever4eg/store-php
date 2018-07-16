<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 13.07.18
 * Time: 13:59
 */

namespace Src\Authorization;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Src\Middleware\RedirectMiddleware;

class GuestMiddleware extends RedirectMiddleware
{
    public function handle(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        if( (new Auth)->isAuth() ) {
            return parent::redirect();
        }
        return $next($request, $response, $next);
    }
}