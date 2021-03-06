<?php
namespace application\controllers;

use application\core\ModelException;
use application\core\Session;
use application\core\View;
use application\entity\User;
use Conf;
use application\core\Controller;
use application\core\Model;
use application\core\Route;
use application\models\Model_Users;

class Controller_Users extends Controller
{
    /* @var $model Model_Users */
    protected $model;
    /* @var $view View */
    protected $view;
    /* @var $session Session */
    protected $session;

    /*===================params for action_dump=========================== */
    private $_dump_dir = "/dump";
    private $_dump_name;
    private $_gzip = false; 		// sql or gzip
    private $_stream = true;		// save in dump dir  and upload from browser

    public function getAcceptedUserType()
    {
        return User::ADMIN;
    }

    private function delete_user_session($user_id){
        $current_session = session_id();
        session_write_close();
        $user = $this->model->getUser($user_id);
        session_id($user->getSessionID());
        session_start();
        $user->setSessionID('');
        $this->model->setUser($user->getID(), $user);
        session_destroy();
        session_write_close();
        session_id($current_session);
        session_start();
    }
    public function action_index()
    {
        $data = $this->model->getAllUsers();
        $this->view->generate('users_view.php', 'template_view.php', $data);
    }
    public function action_edit($user_id)
    {
        if(empty($_POST)){
            $data["user"] = $this->model->getUser($user_id);
            $data["action"] = "edit";
            $data["user_types"] = $this->model->get_user_types();
            $this->view->generate('users_edit_view.php', 'template_view.php', $data);
        } else {
            $user = $this->model->getUser($user_id);
            $user->setLogin(htmlspecialchars($_POST['user_login']));
            $user->setName(htmlspecialchars($_POST['user_name']));
            $user->setType((int)$_POST['user_type']);
            if (!empty($_POST['password'])) {
                $user->setPassword(md5(md5($_POST['password'].Conf::SECURE_SALT)));
            }
            $this->model->setUser($user_id, $user);
            Route::redirect('users');
        }
    }
    public function action_delete($user_id)
    {
        if ($this->session['user_id'] != $user_id) {
            $this->model->deleteUser($user_id);
        }
        Route::redirect('users');
    }
    public function action_logout($user_id)
    {
        $this->delete_user_session($user_id);
        Route::redirect('users');
    }
    public function action_create() {
        if(empty($_POST)){
            $data["action"] = "create";
            $data["user_types"] = $this->model->get_user_types();
            $this->view->generate('users_edit_view.php', 'template_view.php', $data);
        } else {
            $data["error"] = null;
            $user = new User();
            $user->setLogin(htmlspecialchars($_POST['user_login']));
            $user->setName(htmlspecialchars($_POST['user_name']));
            $user->setType((int)$_POST['user_type']);
            $user->setPassword(md5(md5($_POST['password'].Conf::SECURE_SALT)));
            try {
                $this->model->addUser($user);
                Route::redirect('users');
            } catch (ModelException $e) {
                $data["error"] = $e->getMessage();
                $data["action"] = "create";
                $data["user"] = $user;
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
                try {
                    $this->model->getUserByLogin($new_login);
                    $is_available = false;
                } catch (ModelException $e) {
                    $is_available = true;
                }
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
                $res1 = $this->model->sql("SHOW CREATE TABLE ".$tables[$k]['Tables_in_'.Conf::DB_NAME]);
                $query="\nDROP TABLE IF EXISTS `".$tables[$k]['Tables_in_'.Conf::DB_NAME]."`;\n".$res1[0]['Create Table'].";\n";
                fwrite($fp, $query);
                $query="";

                $r_ins = $this->model->select($tables[$k]['Tables_in_'.Conf::DB_NAME]);

                $count_r_ins = count($r_ins);
                if($count_r_ins > 0){
                    $query_ins = "\nINSERT INTO `".$tables[$k]['Tables_in_'.Conf::DB_NAME]."` VALUES ";
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