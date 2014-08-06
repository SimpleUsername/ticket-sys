<?php
/**
 * Created by PhpStorm.
 * User: Ilia
 * Date: 06.08.14
 * Time: 3:16
 */

namespace test\fake;


use application\core\Session;

class FakeSession extends Session {

    public static $arr;
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
        $this->_session_id = 'aaaaaaaaaaa';
    }
    public function __set ($name , $value)
    {
        if (null !== static::$_instance) {
            self::$arr[$name] = $value;
        }
    }
    private function __clone() {}
    private function __wakeup() {}

    public function offsetExists($offset)
    {
        if (null !== static::$_instance) {
            return isset(self::$arr[$offset]);
        } else {
            return false;
        }
    }
    public function offsetGet($offset)
    {
        return self::$arr[$offset];
    }
    public function offsetSet($offset, $value)
    {
        self::$arr[$offset] = $value;
    }
    public function offsetUnset($offset)
    {
        unset(self::$arr[$offset]);
    }
    public function getID()
    {
        return '11111111111';
    }

} 