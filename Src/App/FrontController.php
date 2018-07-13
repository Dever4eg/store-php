<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 13.07.18
 * Time: 12:35
 */

namespace Src\App;


use Src\App;

abstract class FrontController
{
    public final function __construct()
    {
        App::init();
    }
}