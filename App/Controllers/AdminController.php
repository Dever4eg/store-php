<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 17.07.18
 * Time: 12:22
 */

namespace App\Controllers;


use App\Models\Order;
use App\Models\OrderedProducts;
use App\Models\Product;
use App\Models\User;
use Psr\Http\Message\ServerRequestInterface;
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

    public function orders()
    {
        $orders = Order::query()->with('user')->get();

        $view = new View('admin/orders');
        $view->withParam('orders', $orders);

        return $view->getHtmlResponse();
    }

    public function order(ServerRequestInterface $request)
    {
        $id = $request->getQueryParams()['id'];

        $order = Order::query()
            ->with('products')
            ->with('user')
            ->where('id', '=', $id)
            ->one();

        $products = OrderedProducts::addProductFieldsForArray($order->products);

        $view = (new View('admin/order') )
            ->withParam('order', $order)
            ->withParam('products', $products);

        return $view->getHtmlResponse();
    }
}