<?php

require_once __DIR__ . "/../vendor/autoload.php";

const APP_PATH = __DIR__ . "/../app";

//\Src\App::run();

try {
    $name = \Src\Session::instance()
        ->setName("MY_SESSION")
        ->start()
        ->getName();
} catch (Exception $e) {
    var_dump($e);
}

echo $name;