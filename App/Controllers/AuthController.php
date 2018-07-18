<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 12.07.18
 * Time: 13:52
 */

namespace App\Controllers;


use App\Models\User;
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

        $email = $request['login'];
        $password = $request['password'];

        // TODO: validation

        $user = User::query()
            ->with('role')
            ->where('email', '=', $email)
            ->where('password', '=', User::hashPassword($password))
            ->one();

        if(empty($user)) {
            App::getSession()->setFlashMessage(new FlashMessage(
                'error',
                'Login failed',
                'Login or password is invalid'
            ));
            return new RedirectResponse("/login");
        }

        (new Auth)->auth($user);
        App::getSession()->setFlashMessage(new FlashMessage(
            'success',
            'Login success',
            'You are logged in'
        ));
        return new RedirectResponse("/account");

    }

    public function logout()
    {
        (new \Src\Authorization\Auth())->logout();
        return new RedirectResponse('/');
    }
}