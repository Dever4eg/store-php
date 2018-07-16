<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 16.07.18
 * Time: 17:36
 */

namespace App\Controllers;


use Src\View;

class CartController
{
    public function all()
    {

        return (new View('cart'))->getHtmlResponse();
    }
}