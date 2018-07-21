<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 06.07.18
 * Time: 13:02
 */

namespace Src;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use Src\Middleware\ErrorHandlerMiddleware;
use Src\Middleware\MiddlewareHandler;
use Src\Middleware\RouterMiddleware;
use Src\App\Container;
use Src\Routing\Router;
use Src\Logging\Logger;
use Src\Session\Session;
use Src\View\ViewConfig;

/**
 * Class App
 * @package Src
 * @method static Router getRouter()
 * @method static Session getSession()
 * @method static Config getConfig()
 * @method static Logger getLogger()
 * @method static MiddlewareHandler getMiddleware()
 * @method static ViewConfig getViewConfig()
 */
class App extends Container
{


    public static function run()
    {

        $request = ServerRequestFactory::fromGlobals();

        $middleware = self::getMiddleware();

        $middleware->register(new ErrorHandlerMiddleware());
        $middleware->register(new RouterMiddleware());


        $response = $middleware->run($request, function (ServerRequestInterface $request) {
            $handler = App::getRouter()->getMatch($request)->handler;
            return $handler($request);
        });

        if(empty(ob_get_length()) && isset($response ) && $response instanceof ResponseInterface) {
            (new SapiEmitter)->emit($response);
        }

    }

    public static function registerCoreComponents()
    {
        self::addConponents([
            'Router'        => Router::class,
            'Middleware'    => MiddlewareHandler::class,
            'Config'        => Config::class,
            'Session'       => Session::class,
            'Logger'        => Logger::class,
            'ViewConfig'    => ViewConfig::class,
        ]);
    }
}