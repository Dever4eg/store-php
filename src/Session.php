<?php
/**
 * Created by PhpStorm.
 * User: dever
 * Date: 7/8/18
 * Time: 22:45
 */

namespace Src;


use Src\Traits\Singleton;

class Session
{
    use Singleton;

    public $name;
    public $id;

    public function getName()
    {
        if(session_status() !== PHP_SESSION_ACTIVE)
            throw new \Exception("session does not start");
        return session_name();
    }

    public function setName($name)
    {
        if(session_status() !== PHP_SESSION_NONE)
            throw new \Exception("Session already start");
        session_name($name);
        return $this;
    }

    public function start()
    {
        session_start();
        return $this;
    }
}