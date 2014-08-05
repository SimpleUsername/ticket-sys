<?php
namespace application\controllers;

use application\core\ModelException;
use application\core\View;
use Conf;
use application\entity\User;
use application\core\Controller;
use application\core\Model;
use application\models\Model_User;

class Controller_User extends Controller {

    /* @var $model Model_User */
    private $model;
    private $view;

    public function __construct(Model $model, View $view) {
        $this->model = $model;
        $this->view = $view;

        parent::__construct();
    }

    public function action_login() {

        if (!isset($_POST['login']) && !isset($_POST['password'])) {
            if (!$this->isAuthorized()) {
                $this->view->generate('login_view.php', 'template_view.php');
            } else {
                $this->redirect("main/index");
            }
        } else {
            try {
                $user = $this->model->getUserByLogin($_POST['login']);
                if ($user->getPassword() == md5(md5($_POST['password'] . Conf::SECURE_SALT))) {
                    $user->setSessionID(session_id());
                    $user->setIP($_SERVER['REMOTE_ADDR']);
                    $this->model->setUser($user->getID(), $user);
                    $_SESSION['authorized'] = 1;
                    $_SESSION['user_id'] = $user->getID();
                    $_SESSION['user_login'] = $user->getLogin();
                    $_SESSION['user_name'] = $user->getLogin();
                    $_SESSION['user_type'] = $user->getType();
                    $_SESSION['user_admin'] = $user->getType() & User::ADMIN;
                    $_SESSION['user_manager'] = $user->getType() & User::MANAGER;
                    $_SESSION['user_seller'] = $user->getType() & User::SELLER;
                    $this->redirect("main/index");
                } else {
                    $_SESSION['user_login'] = htmlspecialchars($_POST['login']);
                    $_SESSION['error'] = 'Неправильный пароль';
                    $this->redirect("user/login");
                }
            } catch (ModelException $e) {
                $_SESSION['error'] = $e->getMessage();
                $this->redirect("user/login");
            }
        }
    }

    public function action_logout() {
        $user = $this->model->getUser($_SESSION['user_id']);
        if ($user->getSessionID() == session_id()) {
            $user->setSessionID('');
            $this->model->setUser($user->getID(), $user);
        }
        $_SESSION['authorized'] = 0;
        $this->redirect("user/login");
    }

    public function action_password() {
        if (empty($_POST)) {
            $this->view->generate('user_password_view.php', 'template_view.php');
        } else {
            $user = $this->model->getUser($_SESSION['user_id']);
            $user->setPassword(md5(md5($_POST['new_password'].Conf::SECURE_SALT)));
            $this->model->setUser($user->getID(), $user);
            $this->redirect("user/logout");
        }
    }



}