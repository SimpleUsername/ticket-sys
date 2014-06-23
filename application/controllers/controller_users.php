<?php
/**
 * Created by PhpStorm.
 * User: Ilia
 * Date: 19.06.14
 * Time: 12:46
 */
class Controller_Users extends Controller {
    public function __construct()
    {
        $this->model = new Model_Users();
        parent::__construct();
    }

    public function action_index()
    {
        $data = $this->model->get_users();
        $this->view->generate('users_view.php', 'template_view.php', $data);
    }
    public function action_edit($user_id)
    {
        if(empty($_POST)){
            $data = $this->model->get_users(array("user_id" => (int)$user_id));
            $data["action"] = "edit";
            $data["user_types"] = $this->model->get_user_types();
            $this->view->generate('users_edit_view.php', 'template_view.php', $data);
        } else {
            $user_data = array(
                "user_login" => $_POST['user_login'],
                "user_name" => $_POST['user_name'],
                "user_type_id" => $_POST['user_type']
            );
            if (!empty($_POST['password'])) {
                $user_data["user_password"] = md5(md5($_POST['password']));
            }
            $this->model->edit_user($user_id, $user_data);
            $this->redirect('users');
        }
    }
    public function action_delete($user_id)
    {
        if ($_SESSION['user_id'] != $user_id) {
            $data = $this->model->delete_user($user_id);
        }
        $this->redirect('users');
    }
    public function action_create() {
        if(empty($_POST)){
            $data["action"] = "create";
            $data["user_types"] = $this->model->get_user_types();
            $this->view->generate('users_edit_view.php', 'template_view.php', $data);
        } else {
            $data["error"] = null;
            $user_data = array(
                "user_login" => $_POST['user_login'],
                "user_name" => $_POST['user_name'],
                "user_type_id" => $_POST['user_type'],
                "user_password" => md5(md5($_POST['password']))
            );
            if ($this->model->create_user($user_data)) {
                $this->redirect('users');
            } else {
                $data["error"] = "Ошибка добавления пользователя!";
                $data["action"] = "create";
                $data["user_login"] =  $_POST['user_login'];
                $data["user_type"] =  $_POST['user_type'];
                $data["user_types"] = $this->model->get_user_types();
                $this->view->generate('users_edit_view.php', 'template_view.php', $data);
            }
        }
    }
}