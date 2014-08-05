<?php
namespace application\controllers;

use application\core\Authority;
use application\core\Route;
use Conf;
use application\entity\User;
use application\core\Model;
use application\core\ModelException;
use application\core\View;
use application\core\Controller;
use application\core\Session;
use application\models\Model_User;

class Controller_User extends Controller
{
    /* @var $model Model_User */
    protected $model;
    /* @var $view View */
    protected $view;
    /* @var $session Session */
    protected $session;

    public function getAcceptedUserType()
    {
        return User::SELLER | User::MANAGER | User::ADMIN;
    }
    public function action_login() {
        if (!isset($_POST['login']) && !isset($_POST['password'])) {
            if (!Authority::isLoggedIn()) {
                $this->view->generate('login_view.php', 'template_view.php');
            } else {
                Route::redirect("main/index");
            }
        } else {
            try {
                $user = $this->model->getUserByLogin($_POST['login']);
                if ($user->getPassword() == md5(md5($_POST['password'] . Conf::SECURE_SALT))) {
                    $user->setSessionID(session_id());
                    $user->setIP($_SERVER['REMOTE_ADDR']);
                    $this->model->setUser($user->getID(), $user);
                    $this->session['authorized'] = 1;
                    $this->session['user_id'] = $user->getID();
                    $this->session['user_login'] = $user->getLogin();
                    $this->session['user_name'] = $user->getLogin();
                    $this->session['user_type'] = $user->getType();
                    $this->session['user_admin'] = $user->getType() & User::ADMIN;
                    $this->session['user_manager'] = $user->getType() & User::MANAGER;
                    $this->session['user_seller'] = $user->getType() & User::SELLER;
                    Route::redirect("main/index");
                } else {
                    $this->session['user_login'] = htmlspecialchars($_POST['login']);
                    $this->session['error'] = 'Неправильный пароль';
                    Route::redirect("user/login");
                }
            } catch (ModelException $e) {
                $this->session['error'] = $e->getMessage();
                Route::redirect("user/login");
            }
        }
    }

    public function action_logout() {
        $user = $this->model->getUser($this->session['user_id']);
        if ($user->getSessionID() == session_id()) {
            $user->setSessionID('');
            $this->model->setUser($user->getID(), $user);
        }
        $this->session['authorized'] = 0;
        Route::redirect("user/login");
    }

    public function action_password() {
        if (empty($_POST)) {
            $this->view->generate('user_password_view.php', 'template_view.php');
        } else {
            $user = $this->model->getUser($this->session['user_id']);
            $user->setPassword(md5(md5($_POST['new_password'].Conf::SECURE_SALT)));
            $this->model->setUser($user->getID(), $user);
            Route::redirect("user/logout");
        }
    }



}