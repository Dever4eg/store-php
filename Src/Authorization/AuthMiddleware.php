<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 13.07.18
 * Time: 13:38
 */

namespace Src\Authorization;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Src\Exceptions\Http\Error404Exception;
use Src\Middleware\MiddlewareInterface;
use Zend\Diactoros\Response\RedirectResponse;

class AuthMiddleware implements MiddlewareInterface
{
    protected $redirect_url;
    protected $status;

    public function __construct($redirect_url, $status = 302)
    {
        $this->redirect_url = $redirect_url;
        $this->status = $status;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $next
     * @return ResponseInterface
     * @throws Error404Exception
     */
    public function handle(RequestInterface $request, ResponseInterface $response, $next)
    {
        if( !(new Auth)->isAuth() ) {
            if($this->status == 404) {
                throw new Error404Exception;
            }
            return new RedirectResponse($this->redirect_url, $this->status);
        }
        return $next($request, $response, $next);
    }
}