<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 17.07.18
 * Time: 16:48
 */

namespace App\Controllers;


use App\Models\Cart;
use Psr\Http\Message\ServerRequestInterface;
use Src\Authorization\Auth;

class OrdersController
{
    public function new()
    {
        $products = (new Cart())->all();
        $user = (new Auth())->getUser();

        var_dump($user);
        var_dump($products);
    }
}