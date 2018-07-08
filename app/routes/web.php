<?php

/*
 * Using Route::add('method', '/url', callback);
 *       Route::add('method', '/url', [new Controller, 'action']);
 */

use App\Controllers;

$router = \Src\App::getRouter();

$router->add('get', '/', function () {
    ob_start();

    $products = require APP_PATH . '/products_data.php';
    require APP_PATH . '/views/main.php';

    $content = ob_get_clean();
    require APP_PATH . "/views/layout.php";
});

$router->add('get', '/details', function () {
    ob_start();


    $products = require APP_PATH . '/products_data.php';
    $product = $products[$_GET['id']];

    require APP_PATH . '/views/product.php';

    $content = ob_get_clean();
    require APP_PATH . "/views/layout.php";
});

$router->add('get', '/cart', function () {
    ob_start();

    require APP_PATH . '/views/cart.php';

    $content = ob_get_clean();
    require APP_PATH . "/views/layout.php";
});


$router->add('get', '/test', [new Controllers\TestController, 'index']);
$router->add('get', '/login', function() {
    echo "login page";
});
$router->add('post', '/login', function() {
    echo "logining";
});
