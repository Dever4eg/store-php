<?php

/*
 * Using Route::add('method', '/url', callback);
 *       Route::add('method', '/url', [new Controller, 'action']);
 */

use App\Controllers;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use \Src\Routing\Router;
use Src\View;

$router = \Src\App::getRouter();

$router->get('/',               [new Controllers\ProductsController(), 'index']);
$router->get('/details',        [new Controllers\ProductsController(), 'show']);
$router->get('/cart',           [new View("cart"), 'getHtmlResponse']);

$router->group(function (Router $router) {

    $router->get('/account',    [new View("account"), 'getHtmlResponse']);
    $router->get('/logout',     [new Controllers\AuthController(), 'logout']);

}, ['middleware' => [new \Src\Authorization\AuthMiddleware('/login')]]);


$router->group(function (Router $router) {

    $router->get('/login',      [new View("login"), 'getHtmlResponse']);
    $router->post('/login',     [new Controllers\AuthController(), 'login']);

}, ['middleware' => new \Src\Authorization\GuestMiddleware('/account')]);