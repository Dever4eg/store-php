<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 11.07.18
 * Time: 9:58
 */

namespace Src\Authorization;


use Src\App;
use Src\Session\FlashMessage;

class Auth
{
    public function isAuth()
    {
        $session = App::getSession();
        if(!$session->cookieExist()) {
            return false;
        }
        $session->start();

        if(!$session->contains('login')) {
            return false;
        }

//        return $this->checkToken($session->get('login'), $session->get('token'));
        return $session->get('login');
    }

    public function auth($login)
    {
        App::getSession()->start()
            ->set("login", $login);
        return true;
    }

    public function logout()
    {
        $session = App::getSession()->start();
        $session->delete('login');
    }

    public function getLogin()
    {
        if(!$this->isAuth()) {
            return false;
        }
        return App::getSession()->get('login');
    }
}