<?php
/**
 * Created by PhpStorm.
 * User: dever
 * Date: 7/8/18
 * Time: 22:45
 */

namespace Src\Session;


use Src\App\AppSingleComponent;
use Src\Exceptions\Session\SessionAlreadyRunException;

class Session implements AppSingleComponent
{
    protected $name;
    protected $id;
    protected $save_path;
    protected $handler;

    public function __construct()
    {
    }

    public function sessionExist()
    {
        return session_status() == PHP_SESSION_ACTIVE;
    }

    public function cookieExist()
    {
        $name = $this->getName();
        return isset($_COOKIE[$name ? $name : 'PHPSESSID']);
    }

    public function getName()
    {
        if($this->sessionExist()) {
            return session_name();
        }
        if (!empty($this->name)) {
            return $this->name;
        }
        return false;
    }

    /**
     * @param $name
     * @return $this
     * @throws SessionAlreadyRunException
     */
    public function setName($name)
    {
        if($this->sessionExist()) {
            throw new SessionAlreadyRunException("Sou can set the name only before the session start");
        }
        $this->name = $name;
        session_name($name);
        return $this;
    }

    public function getId()
    {
        if($this->sessionExist()) {
            return session_id();
        }
        if (!empty($this->id)) {
            return $this->id;
        }
        return false;
    }

    /**
     * @param $id
     * @return $this
     * @throws SessionAlreadyRunException
     */
    public function setId($id)
    {
        if($this->sessionExist()) {
            throw new SessionAlreadyRunException("Sou can set id only before the session start");
        }
        $this->id = $id;
        session_id($id);
        return $this;
    }

    /**
     * @param $path
     * @return $this
     * @throws SessionAlreadyRunException
     */
    public function setSavePath($path)
    {
        if($this->sessionExist()) {
            throw new SessionAlreadyRunException("Sou can set the save path only before the session start");
        }
        $this->save_path = $path;
        session_save_path($path);
        return $this;
    }

    public function start()
    {
        if ($this->sessionExist()) {
            return $this;
        }

        return session_start() ? $this : false;
    }

    public function destroy()
    {
        if (!$this->sessionExist()) {
            return false;
        }

        $_SESSION = [];
        return session_destroy();
    }

    public function set($key, $value)
    {
        if(!$this->sessionExist()) {
            $this->start();
        }
        $_SESSION[$key] = $value;
        return $this;
    }

    public function get($key)
    {
        if(!$this->sessionExist()) {
            $this->start();
        }
        if($this->contains($key)) {
            return $_SESSION[$key];
        }
        return null;
    }

    public function delete($key)
    {
        if(!$this->sessionExist()) {
            $this->start();
        }
        unset($_SESSION[$key]);
        return $this;
    }

    public function contains($key)
    {
        if(!$this->sessionExist()) {
            $this->start();
        }
        return isset($_SESSION[$key]);
    }


    /**
     * @param \SessionHandlerInterface $sessionHandler
     * @return $this
     * @throws SessionAlreadyRunException
     */
    public function setHandler(\SessionHandlerInterface $sessionHandler)
    {
        if($this->sessionExist()) {
            throw new SessionAlreadyRunException("Sou can set the handler only before the session start");
        }
        session_set_save_handler ( $sessionHandler );

        $this->handler = $sessionHandler;
        return $this;
    }

    public function setFlashMessage(FlashMessageInterface $value)
    {
        $messages = $this->get('flash') ?? [];
        $messages[] = $value;
        $this->set('flash', $messages);
        return $this;
    }


    public function getFlashMessages()
    {
        $messages = $this->get('flash');
        $this->delete('flash');
        return $messages;
    }
}