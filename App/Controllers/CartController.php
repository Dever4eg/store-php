<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 16.07.18
 * Time: 17:36
 */

namespace App\Controllers;


use App\Models\Product;
use App\Services\Cart;
use Psr\Http\Message\ServerRequestInterface;
use Src\App;
use Src\Session\FlashMessage;
use Src\View;
use Zend\Diactoros\Response\RedirectResponse;

class CartController
{
    public function all()
    {
        $cart = new Cart();

        $view = new View('cart');
        $view->withParam('cart', $cart->all());

        return $view->getHtmlResponse();
    }

    public function add(ServerRequestInterface $request)
    {
        $id = $request->getQueryParams()['id'];
        // TODO: validation

        $product = Product::getById($id);

        $cart = new Cart();
        !empty($product) && $cart->add($product);

        App::getSession()->setFlashMessage(
            new FlashMessage('success', 'Product added to cart', $product->title.' added to cart')
        );

        return new RedirectResponse($request->getServerParams()['HTTP_REFERER'] ?? '/');
    }

    public function remove(ServerRequestInterface $request)
    {
        $id = $request->getQueryParams()['id'];
        // TODO: validation

        $cart = new Cart();
        $cart->remove($id);

        return new RedirectResponse('/cart');
    }
}