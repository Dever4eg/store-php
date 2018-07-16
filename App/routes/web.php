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


// only authorized users
$router->group(function (Router $router) {

    // Only customers
    $router->group(function (Router $router) {
        $router->get('/account',    [new View("account"), 'getHtmlResponse']);
    }, ['middleware' => 'customer']);

    // Only admins
    $router->group(function (Router $router) {
        $router->get('/admin',          [new View('/admin/dashboard'),  'getHtmlResponse']);
        $router->get('/admin/users',    [new View('/admin/users'),      'getHtmlResponse']);
        $router->get('/admin/products', [new View('/admin/products'),   'getHtmlResponse']);
    }, ['middleware' => 'admin']);

    $router->get('/logout',     [new Controllers\AuthController(), 'logout']);

}, ['middleware' => 'auth']);

// only unauthorized users
$router->group(function (Router $router) {
    $router->get('/login',      [new View("login"), 'getHtmlResponse']);
    $router->post('/login',     [new Controllers\AuthController(), 'login']);
}, ['middleware' => 'guest']);