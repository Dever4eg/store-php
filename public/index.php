<?php

require_once __DIR__ . "/../vendor/autoload.php";

const APP_PATH = __DIR__ . "/../app";

//\Src\App::run();

try {
    $session = \Src\Session::instance();

    var_dump($session->sessionExist());

    $session->start();

    var_dump($session->sessionExist());


} catch (Exception $e) {
    var_dump($e);
}

