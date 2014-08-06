<?php
namespace application\controllers;

use application\core\Authority;
use application\core\AuthorityException;
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
                $this->redirect("main/index");
            }
        } else {
            try {
                $user = $this->model->getUserByLogin($_POST['login']);

                $authority = new Authority();
                $authority->setModel($this->model);
                $authority->setSession($this->session);
                $authority->login($user);

                $this->redirect("main/index");
            } catch (ModelException $e) {
                $this->session['error'] = $e->getMessage();
                $this->redirect("user/login");
            } catch (AuthorityException $e) {
                $this->session['error'] = $e->getMessage();
                $this->redirect("user/login");
            }
        }
    }

    public function action_logout() {
        $user = $this->model->getUser($this->session['user_id']);

        $authority = new Authority();
        $authority->setModel($this->model);
        $authority->setSession($this->session);
        $authority->logout($user);

        $this->redirect("user/login");
    }

    public function action_password() {
        if (!isset($_POST['new_password']) && !isset($_POST['new_password_confirm'])) {
            $this->view->generate('user_password_view.php', 'template_view.php');
        } else {
            $user = $this->model->getUser($this->session['user_id']);
            $user->setPassword(md5(md5($_POST['new_password'].Conf::SECURE_SALT)));
            $this->model->setUser($user->getID(), $user);
            $this->redirect("user/logout");
        }
    }
}