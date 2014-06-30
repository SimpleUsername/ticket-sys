<?php
class Controller_User extends Controller {
    /*===================params for action_dump=========================== */
    private $_dump_dir = "dump";
    private $_dump_name;
    private $_gzip = false; 		// sql or gzip
    private $_stream = true;		// save in dump dir  and upload from browser

    public function __construct() {
        $this->model = new Model_User();
        parent::__construct();
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
                $user_hash = session_id();
                $user_ip = $_SERVER['REMOTE_ADDR'];
                $this->model->set_user_login_data($user['user_id'], $user_hash, $user_ip);
                $_SESSION['authorized'] = 1;
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_login'] = $user['user_login'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['user_type_id'] = $user['user_type_id'];
                $this->redirect("user");
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

    public function action_dump(){
        $this->_dump_name = date("Y-m-d_h_i").".sql";

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