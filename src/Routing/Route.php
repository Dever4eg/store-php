<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 06.07.18
 * Time: 13:09
 */

namespace Src\Routing;


/**
 * @method static void add($method, $url, $handler, $name = null) set route
 * @method static array getAll() return all defined routes
 * @method static callable getHandler()
 * @method static RouteItem match()
 */
class Route
{
    public static function __callStatic($method, $parameters)
    {
        return call_user_func_array([RouteHandler::instance(), $method], $parameters);
    }
}