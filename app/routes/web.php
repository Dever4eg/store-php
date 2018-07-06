<?php

use Src\Routing\Route;

Route::add('get', '/', function () {
    echo "home";
});

Route::add('get', '/cart', function () {
    echo "cart";
});

Route::add('get', '/test', 'TestController@index');