<?php

/*
 * Using Route::add('method', '/url', callback);
 *       Route::add('method', '/url', [new Controller, 'action']);
 */

use App\Controllers;
use Src\View;

$router = \Src\App::getRouter();

$router->add('get', '/', function () {
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
    echo "logining";
});


$router->add('get', '/mail', function () {
    $mailer = new \Src\Mailer\Mailer(
        new \Src\Mailer\SwiftSmtpMailTransport(
            "email-smtp.us-east-1.amazonaws.com",
            25,
            "AKIAJOOJ2CPOLXELNZAQ",
            "AnZz+pp04NC6qabNROA2mpbHFGU+vwLhV2tp23jBpacy"
        )
    );

    $vardumpMailer = new \Src\Mailer\VarDumpMailTransport();

    $mail = new \Src\Mailer\Mail(
        "Test mail",
        "Message of test mail",
        "dever4eg@gmail.com"
    );

//    $mailer->send($mail);
    $vardumpMailer->send($mail);
});