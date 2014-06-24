<?php
/**
 * Created by PhpStorm.
 * User: Ilia
 * Date: 22.06.14
 * Time: 17:03
 */
class Controller_User extends Controller {
    public function __construct() {
        $this->model = new Model_User();
        parent::__construct();
    }

    private function generateCode($length=6) {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";

        $code = "";

        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {

            $code .= $chars[mt_rand(0,$clen)];
        }

        return $code;

    }
    public function action_index() {
        $this->view->generate('main_view.php', 'template_view.php');
    }

    public function action_login() {
        if (empty($_POST)) {
            if (!isset($_SESSION['authorized']) || (isset($_SESSION['authorized']) && $_SESSION['authorized'] != 1)) {
                $this->view->generate('login_view.php', 'template_view.php');
            } else {
                $this->redirect("user");
            }
        } else {
            if($user = $this->model->get_user($_POST['login'],md5(md5($_POST['password'])))) {
                $user_hash = md5($this->generateCode(10));
                $user_ip = $_SERVER['REMOTE_ADDR'];
                $this->model->set_user_login_data($user['user_id'], $user_hash, $user_ip);
                $_SESSION['authorized'] = 1;
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_login'] = $user['user_login'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['user_type_id'] = $user['user_type_id'];
                // todo
                //setcookie("id", $user['user_id'], time()+60*60*24*30);
                //setcookie("hash", $user_hash, time()+60*60*24*30);
                $this->redirect("user/login");
            } else {
                $this->redirect("user");
            }
        }
    }
    public function action_logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_login']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_type_id']);
        $_SESSION['authorized'] = 0;
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