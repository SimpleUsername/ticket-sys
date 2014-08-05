<?php
/**
 * Created by PhpStorm.
 * User: Ilia
 * Date: 04.08.14
 * Time: 15:17
 */

namespace test\entity;

spl_autoload_register(function ($class) {
    $filename = '../../'.strtolower($class) . '.php';
    if(file_exists($filename)) {
        require_once $filename;
    }
});


use application\entity\User;
use application\entity\UserException;

class UserTest extends \PHPUnit_Framework_TestCase {
    /**
     * @covers \application\entity\User::setID
     */
    public function testSetIdException()
    {
        $user = new User();
        $this->setExpectedException('application\entity\UserException');
        $user->setID('one');

    }

    public function testSettersAndGetters()
    {
        $user = new User();
        $user->setID(1001);
        $user->setLogin($login = $this->generateRandomString(30));
        $user->setName($name = $this->generateRandomString(64));
        $user->setPassword($password = md5(md5($this->generateRandomString(10).\Conf::SECURE_SALT)));
        $user->setType($type = 7);
        $user->setSessionID($sessionID = $this->generateRandomString(32));
        $user->setIP($ip = '234.234.234.123');
        $this->assertEquals($user->getID(), 1001);
        $this->assertEquals($user->getName(), $name);
        $this->assertEquals($user->getLogin(), $login);
        $this->assertEquals($user->getPassword(), $password);
        $this->assertEquals($user->getType(), $type);
        $this->assertEquals($user->getSessionID(), $sessionID);
        $this->assertEquals($user->getIP(), $ip);
        return $user;
    }

    private function generateRandomString($length = 10) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}
 