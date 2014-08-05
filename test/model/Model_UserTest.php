<?php

namespace test\model;

use application\core\Db;
use application\entity\User;
use application\models\Model_User;

spl_autoload_register(function ($class) {
    $filename = '../../'.strtolower($class) . '.php';
    if(file_exists($filename)) {
        require_once $filename;
    }
});

class Model_UserTest extends \PHPUnit_Framework_TestCase {

    private $_db;
    private $_userID;
    private $_userLogin;
    private $_userName;
    private $_userPassword;
    private $_userType;
    private $_userSessionID;
    private $_userIP;

    public function setUp()
    {
        $this->_db = new Db();
        $this->_db->insert('users', array(
            'user_id' => $this->_userID = 1000,
            'user_login' => $this->_userLogin = $this->generateRandomString(30),
            'user_name' => $this->_userName = $this->generateRandomString(64),
            'user_password' => $this->_userPassword = md5(md5($this->generateRandomString(10).\Conf::SECURE_SALT)),
            'user_type' => $this->_userType = 3,
            'user_hash' => $this->_userSessionID = $this->generateRandomString(32),
            'user_ip' => $this->_userIP = '123.123.123.123'
        ));
    }
    public function testGetUser()
    {
        $model = new Model_User($this->_db);
        $user = $model->getUser($this->_userID);
        $this->assertEquals($user->getID(), $this->_userID, 'ID');
        $this->assertEquals($user->getLogin(), $this->_userLogin, 'login');
        $this->assertEquals($user->getName(), $this->_userName, 'name');
        $this->assertEquals($user->getPassword(), $this->_userPassword, 'password');
        $this->assertEquals($user->getType(), $this->_userType, 'type');
        $this->assertEquals($user->getSessionID(), $this->_userSessionID, 'sessionID');
        $this->assertEquals($user->getIP(), $this->_userIP, 'IP');
    }
    public function testGetUserException()
    {
        $model = new Model_User($this->_db);
        $this->setExpectedException('application\core\ModelException');
        $model->getUser(rand(10000,1000000));
    }
    public function testGetUserByLogin()
    {
        $model = new Model_User($this->_db);
        $user = $model->getUserByLogin($this->_userLogin);
        $this->assertEquals($user->getID(), $this->_userID, 'ID');
        $this->assertEquals($user->getLogin(), $this->_userLogin, 'login');
        $this->assertEquals($user->getName(), $this->_userName, 'name');
        $this->assertEquals($user->getPassword(), $this->_userPassword, 'password');
        $this->assertEquals($user->getType(), $this->_userType, 'type');
        $this->assertEquals($user->getSessionID(), $this->_userSessionID, 'sessionID');
        $this->assertEquals($user->getIP(), $this->_userIP, 'IP');
    }
    public function testGetUserByLoginException()
    {
        $model = new Model_User($this->_db);
        $this->setExpectedException('application\core\ModelException');
        $model->getUserByLogin('a');
    }

    public function testSetUser()
    {
        $user = new User();
        $user->setID(1001);
        $user->setLogin($this->generateRandomString(30));
        $user->setName($this->generateRandomString(64));
        $user->setPassword(md5(md5($this->generateRandomString(10).\Conf::SECURE_SALT)));
        $user->setType(7);
        $user->setSessionID($this->generateRandomString(32));
        $user->setIP('234.234.234.123');
        $model = new Model_User($this->_db);
        $affectedUser = $model->setUser($this->_userID, $user);
        $this->assertEquals($affectedUser->getID(), $this->_userID, 'ID');
        $this->assertEquals($affectedUser->getLogin(), $this->_userLogin, 'login');
        $this->assertEquals($affectedUser->getName(), $user->getName(), 'name');
        $this->assertEquals($affectedUser->getPassword(), $user->getPassword(), 'pass');
        $this->assertEquals($affectedUser->getSessionID(), $user->getSessionID(), 'session');
        $this->assertEquals($affectedUser->getType(), $this->_userType, 'type');
        $this->assertEquals($affectedUser->getIP(), $user->getIP(), 'IP');
    }

    public function testSetNotExistingUserException()
    {
        $user = new User();
        $user->setID(1001);
        $user->setLogin($this->generateRandomString(30));
        $user->setName($this->generateRandomString(64));
        $user->setPassword(md5(md5($this->generateRandomString(10).\Conf::SECURE_SALT)));
        $user->setType(7);
        $user->setSessionID($this->generateRandomString(32));
        $user->setIP('234.234.234.123');
        $model = new Model_User($this->_db);
        $this->setExpectedException('application\core\ModelException');
        $model->setUser($user->getID(), $user);
    }

    public function tearDown()
    {
        $this->_db->delete('users', 'user_id = ?', array($this->_userID));
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
 