<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 06.07.18
 * Time: 13:02
 */

namespace Src;


use Src\Routing\Route;

class App
{
    protected static $components = [];
    protected static $instances = [];

    public static function run()
    {
        self::addSystemComponents();

        require_once APP_PATH . "/routes/web.php";
        $handler = Route::getHandler();
        $handler();
    }

    protected static function addSystemComponents()
    {
        self::$components = array_merge(self::$components, [
        ]);
    }

    public static function __callStatic($name, $arguments)
    {
        if(!preg_match("#^get(?P<class>\w+)#", $name, $match)) {
            // TODO: handle error  get dependency
            die("error get dependency");
        }

        $class = ucfirst(strtolower($match['class']));

        if ( key_exists($class, self::$components) ) {
            if( key_exists($class, self::$instances) )
                return self::$instances[$class];
            return (self::$instances[$class] = new self::$components[$class]);
        }

        // TODO: handle error  dependency
        die("error dependency not defined");
    }
}