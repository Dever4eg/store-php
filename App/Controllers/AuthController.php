<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 12.07.18
 * Time: 13:52
 */

namespace App\Controllers;


use Src\Controller;

class AuthController extends Controller
{
    public function login()
    {
        $auth = new \Src\Authorization\Auth();
        $auth->auth();
        header('Location: /');
    }

    public function logout()
    {
        (new \Src\Authorization\Auth())->logout();
        header('Location: /');
    }
}