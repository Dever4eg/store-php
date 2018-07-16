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

/*
 * Only customers
 */
$router->group(function (Router $router) {
    $router->get('/account',    [new View("account"), 'getHtmlResponse']);
}, ['middleware' => ['auth', 'customer']]);


/*
 * Only admins
 */
$router->group(function (Router $router) {

    $router->get('/admin',    function () {
        return new \Zend\Diactoros\Response\HtmlResponse('Admin');
    });

}, ['middleware' => ['auth', 'admin']]);

/*
 * only authorized users
 */
$router->get('/logout',     [new Controllers\AuthController(), 'logout'],
    ['middleware' => 'auth']
);

/*
 * only unauthorized users
 */
$router->group(function (Router $router) {
    $router->get('/login',      [new View("login"), 'getHtmlResponse']);
    $router->post('/login',     [new Controllers\AuthController(), 'login']);
}, ['middleware' => 'guest']);