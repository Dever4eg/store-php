<?php

/*
 * Using Route::add('method', '/url', callback);
 *       Route::add('method', '/url', [new Controller, 'action']);
 */

use App\Controllers;
use Src\View;

$router = \Src\App::getRouter();

$router->add('get', '/', [new Controllers\ProductsController(), 'index']);
$router->add('get', '/details', [new Controllers\ProductsController(), 'show']);
$router->add('get', '/cart', [new View("cart"), 'getHtmlResponse']);

$router->add('post', '/login', [new Controllers\AuthController(), 'login']);
$router->add('get', '/logout', [new Controllers\AuthController(), 'logout']);