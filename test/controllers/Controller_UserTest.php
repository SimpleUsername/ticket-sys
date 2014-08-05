<?php
namespace test\controllers;

require_once '../../autoloader.php';

use application\core\Db;
use application\core\Model;
use application\controllers\Controller_User;
use test\fake\FakeView;

class Controller_UserTest extends \PHPUnit_Framework_TestCase {

    public function testActionLogin()
    {
        $db = new Db();
        $model = new Model($db);
        $view = new FakeView();
        $controller = new Controller_User($model, $view);
        $controller->action_login();
        assert($view->getLastContentView(), 'user/login');
    }
}
 