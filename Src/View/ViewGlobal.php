<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 20.07.18
 * Time: 15:20
 */

namespace Src\View;


use Src\App\AppSingleComponent;

class ViewGlobal implements AppSingleComponent
{
    private $params = [];
    private $functions = [];

    public function __construct()
    {
    }

    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
    }

    public function setFunction($name, $callback)
    {
        $this->functions[$name] = $callback;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getFunctions()
    {
        return $this->functions;
    }
}