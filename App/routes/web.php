<?php

/*
 * Using Route::add('method', '/url', callback);
 *       Route::add('method', '/url', [new Controller, 'action']);
 */

use App\Controllers;
use \Src\Routing\Router;
use Src\View;

$router = \Src\App::getRouter();

$router->get('/',               [new Controllers\ProductsController(), 'index']);
$router->get('/details',        [new Controllers\ProductsController(), 'show']);

$router->get('/cart',           [new Controllers\CartController(), 'all']);
$router->get('/cart/add',       [new Controllers\CartController(), 'add']);
$router->get('/cart/remove',    [new Controllers\CartController(), 'remove']);
$router->get('/cart/increment', [new Controllers\CartController(), 'increment']);
$router->get('/cart/decrement', [new Controllers\CartController(), 'decrement']);


// only authorized users
$router->group(function (Router $router) {

    // Only customers
    $router->group(function (Router $router) {
        $router->get('/account',    [new View("account"), 'getHtmlResponse']);
        $router->post('/order',     [new Controllers\OrdersController(), 'new']);
    }, ['middleware' => 'customer']);

    // Only admins
    $router->group(function (Router $router) {
        $router->get('/admin',          [new View('/admin/dashboard'),     'getHtmlResponse']);
        $router->get('/admin/users',    [new App\Controllers\AdminController(),  'users']);
        $router->get('/admin/products', [new App\Controllers\AdminController(),  'products']);
    }, ['middleware' => 'admin']);

    $router->get('/logout',     [new Controllers\AuthController(), 'logout']);

}, ['middleware' => 'auth']);

// only unauthorized users
$router->group(function (Router $router) {
    $router->get('/login',      [new View("login"), 'getHtmlResponse']);
    $router->post('/login',     [new Controllers\AuthController(), 'login']);
}, ['middleware' => 'guest']);