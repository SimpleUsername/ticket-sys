<?php
class Controller_User extends Controller {


    public function __construct() {
        $this->model = new Model_User();
        parent::__construct();
    }

    public function action_login() {
        if (empty($_POST)) {
            if (!isset($_SESSION['authorized']) || (isset($_SESSION['authorized']) && $_SESSION['authorized'] != 1)) {
                $this->view->generate('login_view.php', 'template_view.php');
            } else {
                $this->redirect("main/index");
            }
        } else {
            if(preg_match("#^[a-z-/.]+$#", $_POST['login'])
                && $user = $this->model->get_user($_POST['login'],md5(md5($_POST['password'])))
            ) {
                $user_hash = session_id();
                $user_ip = $_SERVER['REMOTE_ADDR'];
                $this->model->set_user_login_data($user['user_id'], $user_hash, $user_ip);
                $_SESSION['authorized'] = 1;
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_login'] = $user['user_login'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['user_type_id'] = $user['user_type_id'];
                $this->redirect("main/index");
            } else {
                $_SESSION['user_login'] = htmlspecialchars($_POST['login']);
                $_SESSION['error'] = 'Неправильный логин либо пароль!';
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
            $this->model->set_user_password($_SESSION['user_id'], md5(md5($_POST['new_password'])));
            $this->redirect("user/logout");
        }
    }



}