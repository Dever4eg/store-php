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
use src\Exceptions\Http\Error404Exception;
use Src\Routing\Router;
use Src\Logging\Logger;
use Src\Session\Session;
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
 */
class App
{
    protected static $components = [];
    protected static $instances = [];

    public static function run()
    {
        self::addSystemComponents();

        $err_handler = new ErrorHandler();
        $err_handler->register();
        $err_handler->setDebugMode(self::getConfig()->get("debug"));

        $request = ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

        require_once APP_PATH . "/routes/web.php";

        try {
            $match = self::getRouter()->getMatch($request);

            $response = ($match->handler)($request);

            if($response instanceof ResponseInterface) {
                (new SapiEmitter)->emit($response);
            }

        } catch (Error404Exception $e) {
            $response = new HtmlResponse($e->message);
        } finally {
            if($response instanceof ResponseInterface) {
                (new SapiEmitter)->emit($response);
            }
        }

    }

    protected static function addSystemComponents()
    {
        self::$components = array_merge(self::$components, [
            'Router'        => Router::class,
            'Session'       => Session::class,
            'Config'        => Config::class,
            'Logger'        => Logger::class,
        ]);
    }

    protected static function isInterfaceImplement($class, $interface)
    {
        $interfaces = class_implements($class);
        return isset($interfaces[$interface]);
    }


    public static function __callStatic($name, $arguments)
    {
        // TODO: handle error  get dependency
        if(!preg_match("#^get(?P<class>\w+)#", $name, $match))
            die("error get dependency");

        $component = ucfirst(strtolower($match['class']));

        // TODO: handle error  dependency
        if ( !key_exists($component, self::$components) )
            die("error dependency not defined");

        $class = self::$components[$component];

        if( key_exists($component, self::$instances) ) return self::$instances[$component];

        if (self::isInterfaceImplement($class, AppSingleComponent::class))
            return (self::$instances[$component] = new self::$components[$component]);
    }
}