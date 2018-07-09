<?php
/**
 * Created by PhpStorm.
 * User: dever
 * Date: 7/10/18
 * Time: 00:40
 */

namespace Src;


class View
{
    protected $view;
    protected $params = [];

    protected $twig;

    protected $path = APP_PATH . '/views';

    public function __construct($view)
    {
        $this->view = $view;

        $loader = new \Twig_Loader_Filesystem($this->path);
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => BASE_PATH . '/var/cache/twig',
        ));

        return $this;
    }

    public function withParam($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }

    public function withParams(array $params)
    {
        foreach ($params as $key => $value) {
            $this->withParam($key, $value);
        }
        return $this;
    }

    public function render()
    {
        return $this->twig->render($this->name, $this->params);
    }

    public function display()
    {
        echo $this->render();
    }
}