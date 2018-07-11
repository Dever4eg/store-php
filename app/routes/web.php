<?php

/*
 * Using Route::add('method', '/url', callback);
 *       Route::add('method', '/url', [new Controller, 'action']);
 */

use App\Controllers;
use Src\View;

$router = \Src\App::getRouter();

$router->add('get', '/', function () {
    \Src\App::getLogger()->log("HTTP GET /");
    $products = require APP_PATH . '/products_data.php';
    (new View("main") )
        ->withParam("products", $products)
        ->display();
});

$router->add('get', '/details', function () {

    $products = require APP_PATH . '/products_data.php';
    $product = $products[$_GET['id']];

    (new View("product"))
        ->withParam("product", $product)
        ->display();
});

$router->add('get', '/cart', [new View("cart"), 'display']);
$router->add('get', '/test', [new Controllers\TestController, 'index']);

$router->add('get', '/login', function() {
    echo "login page";
});
$router->add('post', '/login', function() {
    $auth = new \Src\Authorization\Auth();
    if($auth->auth()) {
        header('Location: /home');
    } else {
        header('Location: /');
    }
});

$router->add('get', '/home', function() {
    $auth = new \Src\Authorization\Auth();
    if(!$auth->isAuth()) {
        die('not auth');
    }
    echo "home";

});