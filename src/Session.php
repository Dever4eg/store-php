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

    public function cookieExist()
    {
        try {
            return isset($_COOKIE[$this->getName()]);
        } catch (\Exception $e) {
            if(isset($_COOKIE['PHPSESSID'])) {
                return true;
            }
            throw $e;
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

    public function getId()
    {
        if(!$this->sessionExist())
            throw new \Exception("session does not start");
        return session_id();
    }

    public function setId($id)
    {
        if($this->sessionExist())
            throw new \Exception("Session already start");
        session_id($id);
        return $this;
    }

    public function setSavePath($path)
    {
        if($this->sessionExist()) {
            throw new \Exception("sesstion alredy start");
        }
        session_save_path($path);
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

    public function destroy()
    {
        if (!$this->sessionExist())
            return false;

        $_SESSION = [];
        session_destroy();
    }

    public function set($key, $value)
    {
        if(!$this->sessionExist()) {
            throw new \Exception("sesstion dont start");
        }
        $_SESSION[$key] = $value;
        return $this;
    }

    public function get($key)
    {
        if(!$this->sessionExist()) {
            throw new \Exception("sesstion dont start");
        }
        if($this->contains($key)) {
            return $_SESSION[$key];
        }
        throw new \Exception("key not found");
    }

    public function delete($key)
    {
        if(!$this->sessionExist()) {
            throw new \Exception("sesstion dont start");
        }
        unset($_SESSION[$key]);
        return $this;
    }

    public function contains($key)
    {
        if(!$this->sessionExist()) {
            throw new \Exception("sesstion dont start");
        }
        return isset($_SESSION[$key]);
    }

    public function setHandler(\SessionHandlerInterface $sessionHandler)
    {
        if($this->sessionExist()) {
            throw new \Exception("sesstion alredy start");
        }
        if(!session_set_save_handler ( $sessionHandler)) {
            throw new \Exception("session handler does not changed");
        }
        return $this;
    }
}