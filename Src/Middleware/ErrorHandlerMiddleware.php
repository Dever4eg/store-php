<?php
/**
 * Created by PhpStorm.
 * User: dever
 * Date: 22.07.18
 * Time: 0:04
 */

namespace Src\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Src\App;
use Src\ErrorHandler;
use Src\Exceptions\Http\Error404Exception;
use Src\View;

class ErrorHandlerMiddleware implements MiddlewareInterface
{

    public function handle(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        (new ErrorHandler())
            ->setDebugMode(App::getConfig()->get("debug") ?? false)
            ->register();


        try {
            return $next($request, $response, $next);
        } catch (Error404Exception $e) {
            $response = (new View('errors/error404'))->getHtmlResponse();
            return $response;
        }

    }

}