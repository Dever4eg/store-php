<?php
/**
 * Created by PhpStorm.
 * User: dever
 * Date: 7/8/18
 * Time: 22:45
 */

namespace Src;


use Src\App\AppSingleComponent;
use src\Exceptions\Session\SessionAlreadyRunException;
use src\Exceptions\Session\SessionCanNotStartException;
use src\Exceptions\Session\SessionIsDisabledException;
use src\Exceptions\Session\SessionNotExistException;

class Session implements AppSingleComponent
{
    public function __construct()
    {
    }

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
                throw new SessionIsDisabledException("Session disabled");
                break;
        }
    }

    public function cookieExist()
    {
        try {
            return isset($_COOKIE[$this->getName()]);
        } catch (SessionNotExistException $e) {
            if(isset($_COOKIE['PHPSESSID'])) {
                return true;
            }
            throw $e;
        }
    }

    public function getName()
    {
        if(!$this->sessionExist())
            throw new SessionNotExistException("Session does not start");
        return session_name();
    }

    public function setName($name)
    {
        if($this->sessionExist())
            throw new SessionAlreadyRunException("Sou can set the name only before the session start");
        session_name($name);
        return $this;
    }

    public function getId()
    {
        if(!$this->sessionExist())
            throw new SessionNotExistException("Session does not start");
        return session_id();
    }

    public function setId($id)
    {
        if($this->sessionExist())
            throw new SessionAlreadyRunException("Sou can set the id only before the session start");
        session_id($id);
        return $this;
    }

    public function setSavePath($path)
    {
        if($this->sessionExist()) {
            throw new SessionAlreadyRunException("Sou can set the save path only before the session start");
        }
        session_save_path($path);
        return $this;
    }

    public function start()
    {
        if ($this->sessionExist())
            return $this;
        if(!session_start())
            throw new SessionCanNotStartException("session start exit with error");
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
            throw new SessionNotExistException("You can set session params only after session start");
        }
        $_SESSION[$key] = $value;
        return $this;
    }

    public function get($key)
    {
        if(!$this->sessionExist()) {
            throw new SessionNotExistException("You can get session params only after session start");
        }
        if($this->contains($key)) {
            return $_SESSION[$key];
        }
        return null;
    }

    public function delete($key)
    {
        if(!$this->sessionExist()) {
            throw new SessionNotExistException("You can delete session params only after session start");
        }
        unset($_SESSION[$key]);
        return $this;
    }

    public function contains($key)
    {
        if(!$this->sessionExist()) {
            throw new SessionNotExistException("You can get access to session params only after session start");
        }
        return isset($_SESSION[$key]);
    }


    public function setHandler(\SessionHandlerInterface $sessionHandler)
    {
        if($this->sessionExist()) {
            throw new SessionAlreadyRunException("Sou can set the handler only before the session start");
        }
        if(!session_set_save_handler ( $sessionHandler)) {
            throw new \Exception("session handler does not changed");
        }
        return $this;
    }
}