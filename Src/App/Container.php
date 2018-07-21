<?php
/**
 * Created by PhpStorm.
 * User: dever
 * Date: 21.07.18
 * Time: 23:32
 */

namespace Src\App;


class Container
{
    protected static $components = [];
    protected static $instances = [];

    protected static function isInterfaceImplement($class, $interface)
    {
        $interfaces = class_implements($class);
        return isset($interfaces[$interface]);
    }

    protected static function isUseTrait($class, $trait)
    {
        $traits = class_uses($class);
        return isset($traits[$trait]);
    }

    public static function addComponent($alias, $class)
    {
        if (!self::isInterfaceImplement($class, AppSingleComponent::class)) {
            throw new ContainerException('Component is not implement '. AppSingleComponent::class);
        }
        self::$components[$alias] = $class;
    }

    public static function addComponents(array $components)
    {
        foreach ($components as $alias => $component) {
            self::addComponent($alias, $component);
        }
    }


    public static function __callStatic($name, $arguments)
    {
        if(!preg_match("#^get(?P<class>\w+)#", $name, $match)) {
            trigger_error('Call to undefined method '.static::class.'::'.$name.'()', E_USER_ERROR);
        }

        $component = $match['class'];

        if( key_exists($component, self::$instances) ) {
            return self::$instances[$component];
        }

        if( !key_exists($component, self::$components) ) {
            throw new ContainerException("Component ". $component ." not defined");
        }

        return (self::$instances[$component] = new self::$components[$component]);
    }
}