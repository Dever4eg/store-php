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
use Psr\Http\Message\ServerRequestInterface;
use Src\Authorization\Auth;
use Src\View;

class UsersController
{
    public function account()
    {
        $user = (new Auth)->getUser();
        $orders = Order::query()
            ->where('user_id', '=', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $view = new View('account/orders');
        $view->withParam('orders', $orders);

        return $view->getHtmlResponse();
    }

    public function order(ServerRequestInterface $request)
    {
        $id = $request->getQueryParams()['id'];

        $user = (new Auth)->getUser();
        $order = Order::query()
            ->with('products')
            ->where('user_id', '=', $user->id)
            ->where('id', '=', $id)
            ->one();

        $products = OrderedProducts::addProductFieldsForArray($order->products);

        $view = (new View('account/order') )
            ->withParam('order', $order)
            ->withParam('products', $products);

        return $view->getHtmlResponse();
    }
}