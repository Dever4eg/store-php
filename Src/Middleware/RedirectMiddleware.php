<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 13.07.18
 * Time: 13:52
 */

namespace Src\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Src\Exceptions\Http\Error404Exception;
use Zend\Diactoros\Response\RedirectResponse;

class RedirectMiddleware implements MiddlewareInterface
{

    protected $redirect_url;
    protected $status;

    public function __construct($redirect_url, $status = 302)
    {
        $this->redirect_url = $redirect_url;
        $this->status = $status;
    }

    protected function redirect()
    {
        return new RedirectResponse($this->redirect_url, $this->status);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param $next
     * @return RedirectResponse
     * @throws Error404Exception
     */
    public function handle(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        if($this->status == 404) {
            throw new Error404Exception();
        }
        return $this->redirect();
    }
}