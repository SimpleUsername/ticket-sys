<?php
/**
 * Created by PhpStorm.
 * User: Ilia
 * Date: 19.06.14
 * Time: 12:46
 */
class Controller_Users extends Controller {

    /*===================params for action_dump=========================== */
    private $_dump_dir = "/dump";
    private $_dump_name;
    private $_gzip = false; 		// sql or gzip
    private $_stream = true;		// save in dump dir  and upload from browser


    public function __construct()
    {
        $this->model = new Model_Users();
        parent::__construct();
        if (!$_SESSION['user_admin']) {
            Route::ErrorPage404();
        }
    }
    private function delete_user_session($user_id){
        $current_session = session_id();
        session_write_close();
        $data = $this->model->get_users(array("user_id" => (int)$user_id));
        session_id($data['user_hash']);
        session_start();
        $this->model->clear_user_session_data($user_id);
        session_destroy();
        session_write_close();
        session_id($current_session);
        session_start();
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
                "user_login" => htmlspecialchars($_POST['user_login']),
                "user_name" => htmlspecialchars($_POST['user_name']),
                "user_type" => (int)$_POST['user_type']
            );
            if (!empty($_POST['password'])) {
                $user_data["user_password"] = md5(md5($_POST['password'].SECURE_SALT));
            }
            $this->model->edit_user($user_id, $user_data);
            $this->redirect('users');
        }
    }
    public function action_delete($user_id)
    {
        if ($_SESSION['user_id'] != $user_id) {
            $this->delete_user_session($user_id);
            $this->model->delete_user($user_id);
        }
        $this->redirect('users');
    }
    public function action_logout($user_id)
    {
        $this->delete_user_session($user_id);
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
                "user_login" => htmlspecialchars($_POST['user_login']),
                "user_name" => htmlspecialchars($_POST['user_name']),
                "user_type" => (int)$_POST['user_type'],
                "user_password" => md5(md5($_POST['password'].SECURE_SALT))
            );
            if ($this->model->create_user($user_data)) {
                $this->redirect('users');
            } else {
                $data["error"] = "Ошибка добавления пользователя!";
                $data["action"] = "create";
                $data["user_login"] =  htmlspecialchars($_POST['user_login']);
                $data["user_type"] =  (int)$_POST['user_type'];
                $data["user_types"] = $this->model->get_user_types();
                $this->view->generate('users_edit_view.php', 'template_view.php', $data);
            }
        }
    }

    /**
     * Checking if login available
     * @internal param string $_GET ['old_login'] user current login, if it set
     * @internal param string $_GET ['user_login'] login to check
     * @return json
     */
    public function action_checkLoginAvailableAjax() {
        if (isset($_POST['user_login'])) {
            $new_login = htmlspecialchars($_POST['user_login']);
            if (isset($_GET['old_login']) && $_GET['old_login'] == $new_login) {
                echo json_encode(array(
                    'user_login' => $new_login,
                    'valid' => true,
                ));
            } else {
                $user = $this->model->get_users(array("user_login" => $new_login));
                $is_available = ($user == null);
                echo json_encode(array(
                    'user_login' => $new_login,
                    'valid' => $is_available
                ));
            }
        } else {
            echo json_encode(array(
                'error' => 'Undefined index: user_login'
            ));
        }
    }

    public function setGzip($gzip){
        $this->_gzip = $gzip;
    }
    public function action_dump($gzip_param = null){
        $this->_dump_name = date("Y-m-d_h_i").".sql";
        if($gzip_param == 1){
            $this->setGzip(true);
        }
        $tables = $this->model->sql("SHOW TABLES");

        $fp = fopen( $_SERVER['DOCUMENT_ROOT'].$this->_dump_dir."/".$this->_dump_name, "w" );

        $count_res = count($tables);
        for($k = 0; $k < $count_res ; $k++){

            $query = "";
            if($fp){
                $res1 = $this->model->sql("SHOW CREATE TABLE ".$tables[$k]['Tables_in_'.DB_NAME]);
                $query="\nDROP TABLE IF EXISTS `".$tables[$k]['Tables_in_'.DB_NAME]."`;\n".$res1[0]['Create Table'].";\n";
                fwrite($fp, $query);
                $query="";

                $r_ins = $this->model->select($tables[$k]['Tables_in_'.DB_NAME]);

                $count_r_ins = count($r_ins);
                if($count_r_ins > 0){
                    $query_ins = "\nINSERT INTO `".$tables[$k]['Tables_in_'.DB_NAME]."` VALUES ";
                    fwrite($fp, $query_ins);

                    for($j = 0 ; $j < $count_r_ins; $j++){
                        $query="";
                        foreach($r_ins[$j] as  $field){

                            if ( is_null($field) )$field = "NULL";
                            else $field = "'".$field."'";
                            if ( $query == "" ) $query = $field;
                            else $query = $query.', '.$field;
                        }
                        if($j ==0) {
                            $q="(".$query.")";
                        }
                        else{
                            $q=",(".$query.")";
                        }
                        fwrite($fp, $q);
                    }

                    fwrite($fp, ";\n");
                }
            }
        }
        fclose ($fp);


        if($this->_gzip||$this->_stream){ $data=file_get_contents( $_SERVER['DOCUMENT_ROOT'].$this->_dump_dir."/".$this->_dump_name);
            $ofdot="";
            if($this->_gzip){
                $data = gzencode($data, 9);
                unlink( $_SERVER['DOCUMENT_ROOT'].$this->_dump_dir."/".$this->_dump_name);
                $ofdot=".gz";
            }

            if($this->_stream){
                header('Content-Disposition: attachment; filename='.$this->_dump_name.$ofdot);
                if($this->_gzip) header('Content-type: application/x-gzip'); else header('Content-type: text/plain');
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
                header("Pragma: public");
                echo $data;
            }else{
                $fp = fopen( $_SERVER['DOCUMENT_ROOT'].$this->_dump_dir."/".$this->_dump_name.$ofdot, "w");
                fwrite($fp, $data);
                fclose($fp);
            }
        }
    }
}