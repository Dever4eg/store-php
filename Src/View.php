<?php
/**
 * Created by PhpStorm.
 * User: dever
 * Date: 7/10/18
 * Time: 00:40
 */

namespace Src;


use Src\Authorization\Auth;
use Zend\Diactoros\Response\HtmlResponse;

class View
{
    protected $view;
    protected $params = [];

    protected $twig;

    protected $path;
    protected $extension = "twig";

    public function __construct($view, $template_dir = null)
    {
        if(!empty($template_dir)) {
            $this->path[] = $template_dir;
        }
        $this->path[] = App::getConfig()->get('views_dir') ?? APP_PATH . '/views';

        $this->view = $view . '.' . $this->extension;

        $loader = new \Twig_Loader_Filesystem($this->path);
        $this->twig = new \Twig_Environment($loader, array(
//            'cache' => BASE_PATH . '/var/cache/twig',
        ));


        $this->twig->addFunction( new \Twig_SimpleFunction('getUser', function() {
            return (new Auth())->getUser();
        }));

        $this->twig->addFunction( new \Twig_SimpleFunction('getFlashMessages', function() {
            return App::getSession()->getFlashMessages();
        }));
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
        return $this->twig->render($this->view, $this->params);
    }

    public function display()
    {
        echo $this->render();
    }

    public function getHtmlResponse()
    {
        return new HtmlResponse($this->render());
    }
}