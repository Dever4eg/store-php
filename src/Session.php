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

    public function sessionExist()
    {
        switch (session_status()){
            case PHP_SESSION_ACTIVE:
                return true;
                break;
            case PHP_SESSION_NONE:
                return false;
                break;
            case PHP_SESSION_DISABLED:
                throw new \Exception("Session disabled");
                break;
        }
    }

    public function getName()
    {
        if(!$this->sessionExist())
            throw new \Exception("session does not start");
        return session_name();
    }

    public function setName($name)
    {
        if($this->sessionExist())
            throw new \Exception("Session already start");
        session_name($name);
        return $this;
    }

    public function start()
    {
        if ($this->sessionExist())
            return $this;
        if(!session_start())
            throw new \Exception("session dont start");
        return $this;
    }
}