<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 06.07.18
 * Time: 13:19
 */

namespace Src\Traits;


Trait Singleton
{
    private static $instance;

    private function __construct(){}


    static function instance()
    {
        if(!isset(static::$instance))
            static::$instance = new static;
        return static::$instance;
    }
}