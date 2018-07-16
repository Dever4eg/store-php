<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 13.07.18
 * Time: 12:19
 */

namespace App;


use Src\App;
use Src\App\FrontController as BaseFrontController;

class FrontController extends BaseFrontController
{
    public function run()
    {
        require_once __DIR__ . "/routes/web.php";

        App::getMiddleware()->setAliases([
            'auth'          => new \Src\Authorization\AuthMiddleware('/login'),
            'guest'         => new \Src\Authorization\GuestMiddleware('/account'),
            'admin'         => new \App\Middleware\AdminOnlyMiddleware(),
            'customer'      => new \App\Middleware\CustomerOnlyMiddleware('/admin'),
        ]);

        App::getConfig()->loadConfigFromFile(APP_PATH.'/configs/app.php');

        App::run();
    }
}