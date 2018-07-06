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
    public static function run()
    {
        require_once APP_PATH . "/routes/web.php";
    }
}