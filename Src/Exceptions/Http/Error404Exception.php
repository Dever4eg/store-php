<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 09.07.18
 * Time: 16:53
 */

namespace Src\Exceptions\Http;


class Error404Exception extends HttpException
{
   public $message = '404 error. Page not found';

}