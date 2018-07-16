<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 11.07.18
 * Time: 9:58
 */

namespace Src\Authorization;


use Src\App;

class Auth
{
    public function isAuth()
    {
        $session = App::getSession();
        if(!$session->cookieExist()) {
            return false;
        }
        $session->start();

        if(!$session->contains('user')) {
            return false;
        }

        return $session->get('user');
    }

    public function auth($user)
    {
        App::getSession()->start()
            ->set("user", $user);
        return true;
    }

    public function logout()
    {
        $session = App::getSession()->start();
        $session->delete('user');
    }

    public function getUser()
    {
        if(!$this->isAuth()) {
            return false;
        }
        return App::getSession()->get('user');
    }
}