<?php

/*
 * Using Route::add('method', '/url', callback);
 *       Route::add('method', '/url', [new Controller, 'action']);
 */

use App\Controllers;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Src\View;

$router = \Src\App::getRouter();

$router->get('/', [new Controllers\ProductsController(), 'index']);
$router->get('/details', [new Controllers\ProductsController(), 'show']);
$router->get('/cart', [new View("cart"), 'getHtmlResponse']);

$router->get('/account', [new View("account"), 'getHtmlResponse'])
    ->middleware(new \Src\Authorization\AuthMiddleware('/'));

$router->post('/login', [new Controllers\AuthController(), 'login']);
$router->get('/logout', [new Controllers\AuthController(), 'logout']);