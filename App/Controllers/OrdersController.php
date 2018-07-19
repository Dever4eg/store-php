<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 17.07.18
 * Time: 16:48
 */

namespace App\Controllers;


use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderedProducts;
use Psr\Http\Message\ServerRequestInterface;
use Src\App;
use Src\Authorization\Auth;
use Src\Controller;
use Src\Session\FlashMessage;
use Zend\Diactoros\Response\RedirectResponse;

class OrdersController extends Controller
{
    public function new()
    {
        $cart = new Cart();
        $user = (new Auth())->getUser();

        $order = new Order();
        $order->cost = $cart->sum();
        $order->associate('user', $user)->save();

        $products = OrderedProducts::fromCart($cart);
        $products = $order->associate('products', $products);

        $cart->clear();

        OrderedProducts::insertArray($products);

        App::getSession()->setFlashMessage(new FlashMessage(
            'success',
            'Success',
            'Your products success ordered'
        ));

        return new RedirectResponse('/account');
    }
}