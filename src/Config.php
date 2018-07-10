<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 10.07.18
 * Time: 10:08
 */

namespace src;


use Src\App\AppSingleComponent;

class Config implements AppSingleComponent
{
    public $params = [];

    public function __construct()
    {
        $this->params = array_merge($this->params, require APP_PATH . '/configs/app.php');
    }

    public function getAll()
    {
        return $this->params;
    }

    public function get($name)
    {
        return $this->params[$name] ?? null;
    }
}