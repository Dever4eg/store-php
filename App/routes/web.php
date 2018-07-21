<?php

/*
 * Using Route::add('method', '/url', callback);
 *       Route::add('method', '/url', [new Controller, 'action']);
 */

use App\Controllers;
use \Src\Routing\Router;
use Src\View\View;

$router = \Src\App::getRouter();

$router->get('/',               [new Controllers\ProductsController(), 'index']);
$router->get('/details',        [new Controllers\ProductsController(), 'show']);

$router->get('/cart',           [new Controllers\CartController(), 'all']);
$router->get('/cart/add',       [new Controllers\CartController(), 'add']);
$router->get('/cart/remove',    [new Controllers\CartController(), 'remove']);
$router->get('/cart/increment', [new Controllers\CartController(), 'increment']);
$router->get('/cart/decrement', [new Controllers\CartController(), 'decrement']);



$router->get('/test', function () {

    var_dump(

        \App\Models\Role::query()
            ->with('users')
            ->get()

    );
});


// only authorized users
$router->group(function (Router $router) {

    // Only customers
    $router->group(function (Router $router) {
        $router->get('/account',        [new Controllers\UsersController(), 'account']);
        $router->get('/account/order',  [new Controllers\UsersController(), 'order']);
        $router->post('/order',         [new Controllers\OrdersController(), 'new']);
    }, ['middleware' => 'customer']);

    // Only admins
    $router->group(function (Router $router) {
        $router->get('/admin',          [new View('/admin/dashboard'),     'getHtmlResponse']);
        $router->get('/admin/users',    [new App\Controllers\AdminController(),  'users']);
        $router->get('/admin/products', [new App\Controllers\AdminController(),  'products']);
        $router->get('/admin/orders',   [new App\Controllers\AdminController(),  'orders']);
        $router->get('/admin/order',    [new Controllers\AdminController(),     'order']);

        $router->get('/admin/products/new',    [new Controllers\ProductsController(),     'create']);
        $router->post('/admin/products/new',    [new Controllers\ProductsController(),    'store']);
    }, ['middleware' => 'admin']);

    $router->get('/logout',     [new Controllers\AuthController(), 'logout']);

}, ['middleware' => 'auth']);

// only unauthorized users
$router->group(function (Router $router) {
    $router->get('/login',      [new View("auth/login"), 'getHtmlResponse']);
    $router->post('/login',     [new Controllers\AuthController(), 'login']);
}, ['middleware' => 'guest']);