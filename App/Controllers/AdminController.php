<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 17.07.18
 * Time: 12:22
 */

namespace App\Controllers;


use App\Models\Product;
use App\Models\User;
use Src\View;

class AdminController
{
    public function users()
    {
        $users = User::query()->with('role')->get();

        $view = new View('admin/users');
        $view->withParam('users', $users);

        return $view->getHtmlResponse();
    }

    public function products()
    {
        $products = Product::all();

        $view = new View('admin/products');
        $view->withParam('products', $products);

        return $view->getHtmlResponse();
    }
}