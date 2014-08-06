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
    private static $_db;
    private static $_userID;
    private static $_userLogin;
    private static $_userName;
    private static $_userPassword;
    private static $_userType;
    private static $_userSessionID;
    private static $_userIP;

    public static function setUpBeforeClass()
    {
        self::$_db = new Db();
        self::$_db->delete('users', 'user_id = ?', array(self::$_userID = 1000));
        self::$_db->insert('users', array(
            'user_id' => self::$_userID,
            'user_login' => self::$_userLogin = 'test-login',
            'user_name' => self::$_userName = 'Simple Test Name',
            'user_password' => md5(md5((self::$_userPassword = 'qwerty1234567890').\Conf::SECURE_SALT)),
            'user_type' => self::$_userType = 3,
            'user_hash' => self::$_userSessionID = '22g95kobbvdik3ml2b1ef88ge0',
            'user_ip' => self::$_userIP = '123.243.234.100'
        ));
    }

    public function testActionLogin()
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
        assert(!isset($session::$arr));

        $_SERVER['REMOTE_ADDR'] = self::$_userIP;
        $_POST['login'] = self::$_userLogin;
        $_POST['password'] = self::$_userPassword;

        $controller->action_login();
        $this->assertArrayNotHasKey('error', $session);
        $this->assertEquals('login_view.php', $view->getLastContentView());
        $this->assertEquals('main/index', FakeRoute::getLastSection());
        $this->assertEquals(1, $session['authorized']);
        $this->assertEquals(self::$_userID, $session['user_id']);
        $this->assertEquals(self::$_userLogin, $session['user_login']);
        $this->assertEquals(self::$_userType, $session['user_type']);

        return $controller;
    }
    /**
     * @depends testActionLogin
     */
    public function testPasswordChange(Controller_User $controller)
    {
        $session = FakeSession::getInstance();
        unset($_POST);
        $_POST['new_password'] = self::$_userPassword = $this->generateRandomString(10);
        $_POST['new_password_confirm'] = self::$_userPassword;
        $controller->action_password();
        $this->assertEquals('user/logout', FakeRoute::getLastSection());
        $controller->action_logout();
        $this->assertEquals(0, $session['authorized']);

        unset($_POST);
        $_SERVER['REMOTE_ADDR'] = self::$_userIP;
        $_POST['login'] = self::$_userLogin;
        $_POST['password'] = self::$_userPassword;

        $controller->action_login();
        $this->assertArrayNotHasKey('error', $session);
        $this->assertEquals(1, $session['authorized']);

        return $controller;
    }
    /**
     * @depends testPasswordChange
     */
    public function testLogout(Controller_User $controller)
    {
        $session = FakeSession::getInstance();
        unset($_POST);
        $controller->action_logout();
        $this->assertEquals(0, $session['authorized']);
        $this->assertEquals(self::$_userLogin, $session['user_login']);
        $this->assertEquals(2, count($session::$arr));
        return $controller;
    }
    private function generateRandomString($length = 10) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
    public static function tearDownAfterClass()
    {
        self::$_db->delete('users', 'user_id = ?', array(self::$_userID));
    }
}
 