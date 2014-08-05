<?php

namespace application\core;

use ArrayAccess;

class Session implements ArrayAccess {

    private static $_instance;
    private $_session_id;

    public static function getInstance()
    {
        if (null === static::$_instance) {
            static::$_instance = new static;
        }
        return static::$_instance;
    }
    private function __construct()
    {
        if (!headers_sent()) {
            session_start();
            $this->_session_id = session_id();
        }
    }
    public function __set ($name , $value)
    {
        if (null !== static::$_instance) {
            $_SESSION[$name] = $value;
        }
    }
    private function __clone() {}
    private function __wakeup() {}

    public function offsetExists($offset)
    {
        if (null !== static::$_instance) {
            return isset($_SESSION[$offset]);
        } else {
            return false;
        }
    }
    public function offsetGet($offset)
    {
        return $_SESSION[$offset];
    }
    public function offsetSet($offset, $value)
    {
        $_SESSION[$offset] = $value;
    }
    public function offsetUnset($offset)
    {
        unset($_SESSION[$offset]);
    }
}