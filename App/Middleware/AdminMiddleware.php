<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 16.07.18
 * Time: 13:09
 */

namespace App\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Src\Authorization\Auth;
use Src\Exceptions\Http\Error404Exception;

class AdminMiddleware
{
    public function handle(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $auth = new Auth();

        $user = $auth->getUser();
        if($user->role != 'admin') {
            throw new Error404Exception();
        }

        return $next($request, $response, $next);
    }
}