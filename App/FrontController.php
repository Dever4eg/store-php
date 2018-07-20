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
        App::init();


        App::getMiddleware()->setAliases([
            'auth'          => new \Src\Authorization\AuthMiddleware('/login'),
            'guest'         => new \Src\Authorization\GuestMiddleware('/account'),
            'admin'         => new \App\Middleware\AdminMiddleware(),
            'customer'      => new \App\Middleware\CustomerMiddleware('/admin'),
        ]);

        App::getViewConfig()->setFunction('getUser', function() {
            return (new Auth())->getUser();
        });

        App::getViewConfig()->setFunction('getFlashMessages', function() {
            return App::getSession()->getFlashMessages();
        });

        App::getMiddleware()->register(new CartMiddleware());

        App::getConfig()->loadConfigFromFile(APP_PATH.'/configs/app.php');

        require_once __DIR__ . "/routes/web.php";

        App::run();
    }
}