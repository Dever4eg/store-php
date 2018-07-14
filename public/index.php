<?php

require_once __DIR__ . "/../vendor/autoload.php";

const APP_PATH = __DIR__ . "/../App";
const BASE_PATH = __DIR__ . "/..";

(new \App\FrontController)->run();
