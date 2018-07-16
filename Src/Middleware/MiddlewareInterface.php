<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 13.07.18
 * Time: 10:15
 */

namespace Src\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface MiddlewareInterface
{
    public function handle(ServerRequestInterface $request, ResponseInterface $response, $next);
}