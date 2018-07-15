<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 12.07.18
 * Time: 13:52
 */

namespace App\Controllers;


use Psr\Http\Message\RequestInterface;
use Src\App;
use Src\Authorization\Auth;
use Src\Controller;
use Src\Session\FlashMessage;
use Zend\Diactoros\Response\RedirectResponse;

class AuthController extends Controller
{
    public function login(RequestInterface $request)
    {
        $request = $request->getParsedBody();

        $login = $request['login'];
        $password = sha1($request['password']);

        if(!$this->checkUserData($login, $password)) {
            App::getSession()->setFlashMessage(new FlashMessage(
                'error',
                'Login failed',
                'Login or password is invalid'
            ));
            return new RedirectResponse("/login");
        } else {
            (new Auth)->auth($login);
            App::getSession()->setFlashMessage(new FlashMessage(
                'success',
                'Login success',
                'You are logged in'
            ));
            return new RedirectResponse("/account");
        }
    }

    public function logout()
    {
        (new \Src\Authorization\Auth())->logout();
        return new RedirectResponse('/');
    }

    protected function checkUserData($login, $password)
    {
        $user = [

            'login'     => "admin@admin.com",
            'password'  =>  sha1("password"),
        ];

        return ($login == $user['login'] && $password == $user['password']);
    }
}