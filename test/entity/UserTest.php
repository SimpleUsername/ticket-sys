<?php
namespace test\entity;

require_once '../../autoloader.php';

use application\entity\User;

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
 