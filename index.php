<?php


//Получаем адсес без параметров в переменную $route
if(!empty($_SERVER['QUERY_STRING']))
    $route = substr(
        $_SERVER['REQUEST_URI'],0,
        strpos($_SERVER['REQUEST_URI'], $_SERVER['QUERY_STRING'])-1);
else
    $route = $_SERVER['REQUEST_URI'];


ob_start();

if($route == "/" || $route == "/main") {

    $products = require __DIR__ . '/products_data.php';
    require __DIR__ . '/views/main.php';

} elseif($route == "/details") {

    $products = require __DIR__  . '/products_data.php';
    $product = $products[$_GET['id']];


    require __DIR__ . '/views/product.php';

} elseif ($route == "/cart") {
    require __DIR__ . '/views/cart.php';
} else {
    header("HTTP/1.0 404 Not Found");
    echo "404 page not found";
}

$content = ob_get_clean();

require __DIR__ . "/views/layout.php";
