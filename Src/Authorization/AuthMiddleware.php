<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 13.07.18
 * Time: 13:38
 */

namespace Src\Authorization;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Src\Middleware\RedirectMiddleware;

class AuthMiddleware extends RedirectMiddleware
{
    public function handle(RequestInterface $request, ResponseInterface $response, $next)
    {
        if( !(new Auth)->isAuth() ) {
            return parent::redirect();
        }
        return $next($request, $response, $next);
    }
}