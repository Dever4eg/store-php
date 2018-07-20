<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 20.07.18
 * Time: 15:32
 */

namespace App\Middleware;


use App\Models\Cart;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Src\App;
use Src\Middleware\MiddlewareInterface;

class CartMiddleware implements MiddlewareInterface
{
    public function handle(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $cart = new Cart();

        App::getViewConfig()->setParam('cart', $cart->all());
        App::getViewConfig()->setParam('cartProductsSum', $cart->sum());

        return $next($request, $response, $next);
    }
}