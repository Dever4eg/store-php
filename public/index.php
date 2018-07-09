<?php

require_once __DIR__ . "/../vendor/autoload.php";

const APP_PATH = __DIR__ . "/../app";
const BASE_PATH = __DIR__ . "/..";

//\Src\App::run();

try {
    $session = \Src\Session::instance();

    $session->start();



} catch (Exception $e) {
    var_dump($e);
}

