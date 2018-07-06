<?php

use Src\Routing\Route;

Route::add('get', '/home', function () {
    echo "home";
});

Route::add('get', '/cart', function () {
    echo "cart";
});
