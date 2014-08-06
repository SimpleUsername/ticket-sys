<?php
namespace test\controllers;

require_once '../../autoloader.php';

use application\core\Db;
use application\core\Model;
use application\controllers\Controller_User;
use application\core\Route;
use application\core\Session;
use application\models\Model_User;
use test\fake\FakeSession;
use test\fake\FakeView;
use test\fake\FakeRoute;

class Controller_UserTest extends \PHPUnit_Framework_TestCase {
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
            'user_password' => md5(md5(($this->_userPassword = $this->generateRandomString(10)).\Conf::SECURE_SALT)),
            'user_type' => $this->_userType = 3,
            'user_hash' => $this->_userSessionID = $this->generateRandomString(32),
            'user_ip' => $this->_userIP = '123.123.123.123'
        ));
    }

    public function testAllActions()
    {
        $db = new Db();
        $model = new Model_User($db);
        $view = new FakeView();
        $session = FakeSession::getInstance();
        $controller = new Controller_User();
        $controller->setView($view);
        $controller->setModel($model);
        $controller->setSession($session);
        $controller->setRouter('test\fake\FakeRoute::redirect');
        $controller->action_login();
        $this->assertEquals('login_view.php', $view->getLastContentView());
        $this->assertEquals(null, FakeRoute::getLastSection());
        $_SERVER['REMOTE_ADDR'] = $this->_userIP;
        
        $_POST['login'] = $this->_userLogin;
        $_POST['password'] = $this->_userPassword;
        $controller->action_login();
        $this->assertArrayNotHasKey('error', $session);
        $this->assertEquals('login_view.php', $view->getLastContentView());
        $this->assertEquals('main/index', FakeRoute::getLastSection());
        $this->assertArrayHasKey('authorized', $session);

        $_POST['new_password'] = '1234567890';
        $_POST['new_password_confirm'] = '1234567890';
        $controller->action_password();
        $this->assertEquals('user/logout', FakeRoute::getLastSection());

        $_POST['password'] = '1234567890';
        $controller->action_login();
        $this->assertEquals('main/index', FakeRoute::getLastSection());
    }

    private function generateRandomString($length = 10) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
    public function tearDown()
    {
        $this->_db->delete('users', 'user_id = ?', array($this->_userID));
    }
}
 