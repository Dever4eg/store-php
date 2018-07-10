<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 06.07.18
 * Time: 13:02
 */

namespace Src;


use Src\App\AppSingleComponent;
use src\Exceptions\Http\Error404Exception;
use Src\Routing\Router;

/**
 * Class App
 * @package Src
 * @method static Router getRouter()
 * @method static Session getSession()
 * @method static Config getConfig()
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

        $debug = self::getConfig()->get("debug");

        $err_handler->setDebugMode($debug);


        require_once APP_PATH . "/routes/web.php";

        try {
            $handler = self::getRouter()->getHandler();
        } catch (Error404Exception $e) {
            echo "404 error. Page not found";
        }

        $handler();
    }

    protected static function addSystemComponents()
    {
        self::$components = array_merge(self::$components, [
            'Router'        => Router::class,
            'Session'       => Session::class,
            'Config'        => Config::class,
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