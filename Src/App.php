<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 06.07.18
 * Time: 13:02
 */

namespace Src;


use Psr\Http\Message\ResponseInterface;
use Src\App\AppSingleComponent;
use Src\App\Container;
use Src\Database\DB;
use src\Exceptions\Http\Error404Exception;
use Src\Middleware\MiddlewareHandler;
use Src\Routing\Router;
use Src\Logging\Logger;
use Src\Session\Session;
use Src\Traits\Singleton;
use Src\View\ViewConfig;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

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
        (new ErrorHandler())
            ->setDebugMode(App::getConfig()->get("debug") ?? false)
            ->register();

        try {
            $request = ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

            $match = self::getRouter()->getMatch($request);
            $middleware = self::getMiddleware();
            if($match->middlewareExist()) {
                foreach ($match->getMiddleware() as $item) {
                    $middleware->register($item);
                }
            }
            $response = $middleware->run($request, $match->handler);

        } catch (Error404Exception $e) {
            $response = (new view('errors/error404'))->getHtmlResponse();
        } finally {
            if(
                empty(ob_get_length()) && isset($response ) && $response instanceof ResponseInterface) {
                (new SapiEmitter)->emit($response);
            }
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