<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 11.07.18
 * Time: 9:58
 */

namespace src\Authorization;


use Src\App;

class Auth
{
    public $session_name = "STORE_AUTH";

    public function isAuth()
    {

    }

    public function auth()
    {
        $login = $_REQUEST['login'];
        $password = password_hash($_REQUEST['password']);
        if(empty($login) || empty($password)) {
            return false;
        }

        if($login == "admin" && $password == password_hash("password")) {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $user_addr = $_SERVER['REMOTE_ADDR'];

            App::getSession()
                ->setName($this->session_name)
                ->start()
                ->set("login", $login)
                ->set("token", sha1($password . $user_agent . $user_addr));
        }
    }

    public function logout()
    {

    }

    public function getLogin()
    {

    }

    protected function CheckUserData($login, $password)
    {

    }


}