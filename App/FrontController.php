<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 13.07.18
 * Time: 12:19
 */

namespace App;


use App\Middleware\CartMiddleware;
use Src\App;
use Src\Authorization\Auth;

class FrontController
{
    public function run()
    {
        App::registerCoreComponents();

        App::getConfig()->loadConfigFromFile(APP_PATH.'/configs/app.php');

        App::getMiddleware()->setAliases([
            'auth'          => new \Src\Authorization\AuthMiddleware('/login'),
            'guest'         => new \Src\Authorization\GuestMiddleware('/account'),
            'admin'         => new \App\Middleware\AdminMiddleware(),
            'customer'      => new \App\Middleware\CustomerMiddleware('/admin'),
        ]);


        App::getViewGlobal()->setFunction('getUser', function() {
            return (new Auth())->getUser();
        });

        App::getViewGlobal()->setFunction('getFlashMessages', function() {
            return App::getSession()->getFlashMessages();
        });

        App::getMiddleware()->register(new CartMiddleware());


        require_once __DIR__ . "/routes/web.php";

        App::run();
    }
}