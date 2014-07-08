<?php
class Controller_User extends Controller {


    public function __construct() {
        $this->model = new Model_User();
        parent::__construct();
    }

    public function action_login() {
        if (empty($_POST)) {
            if (!$this->isAuthorized()) {
                $this->view->generate('login_view.php', 'template_view.php');
            } else {
                $this->redirect("main/index");
            }
        } else {
            if($user = $this->model->get_user($_POST['login'],md5(md5($_POST['password'].SECURE_SALT)))
            ) {
                $user_hash = session_id();
                $user_ip = $_SERVER['REMOTE_ADDR'];
                $this->model->set_user_login_data($user['user_id'], $user_hash, $user_ip);
                $_SESSION['authorized'] = 1;
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_login'] = $user['user_login'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['user_admin'] = $user['user_type'] & 0x04;
                $_SESSION['user_manager'] = $user['user_type'] & 0x02;
                $_SESSION['user_seller'] = $user['user_type'] & 0x01;
                $this->redirect("main/index");
            } else {
                if ($this->model->get_user_by_login($_POST['login'])) {
                    $_SESSION['user_login'] = htmlspecialchars($_POST['login']);
                    $_SESSION['error'] = 'Неправильный пароль!';
                } else {
                    $_SESSION['error'] = 'Неправильный логин!';
                }
                $this->redirect("user/login");
            }
        }
    }
    public function action_logout() {
        $this->model->set_user_login_data($_SESSION['user_id']);
        session_destroy();
        $this->redirect("user/login");
    }
    public function action_password() {
        if (empty($_POST)) {
            $this->view->generate('user_password_view.php', 'template_view.php');
        } else {
            $this->model->set_user_password($_SESSION['user_id'], md5(md5($_POST['new_password'].SECURE_SALT)));
            $this->redirect("user/logout");
        }
    }



}