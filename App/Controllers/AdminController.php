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
    public function users(ServerRequestInterface $request)
    {
        $users = User::query()->with('role')->paginate(10, $request);

        $view = new View('admin/users');
        $view->withParam('users', $users['results'])
            ->withParam('pagination', $users);

        return $view->getHtmlResponse();
    }

    public function products(ServerRequestInterface $request)
    {
        $products = Product::query()->paginate(10, $request);

        $view = new View('admin/products/index');
        $view->withParam('products', $products['results'])
            ->withParam('pagination', $products);

        return $view->getHtmlResponse();
    }

    public function orders(ServerRequestInterface $request)
    {
        $orders = Order::query()
            ->with('user')
            ->paginate(10, $request);

        $view = new View('admin/orders');
        $view->withParam('orders', $orders['results'])
            ->withParam('pagination', $orders);

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