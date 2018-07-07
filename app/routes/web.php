<?php

/*
 * Using Route::add('method', '/url', callback);
 *       Route::add('method', '/url', [new Controller, 'action']);
 */

use Src\Routing\Route;
use App\Controllers;

Route::add('get', '/', function () {
    ob_start();

    $products = require APP_PATH . '/products_data.php';
    require APP_PATH . '/views/main.php';

    $content = ob_get_clean();
    require APP_PATH . "/views/layout.php";
});

Route::add('get', '/details', function () {
    ob_start();


    $products = require APP_PATH . '/products_data.php';
    $product = $products[$_GET['id']];

    require APP_PATH . '/views/product.php';

    $content = ob_get_clean();
    require APP_PATH . "/views/layout.php";
});

Route::add('get', '/cart', function () {
    ob_start();

    require APP_PATH . '/views/cart.php';

    $content = ob_get_clean();
    require APP_PATH . "/views/layout.php";
});


Route::add('get', '/test', [new Controllers\TestController, 'index']);