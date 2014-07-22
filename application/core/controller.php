<?php

class Controller {

    public $model;
    public $view;

    public function __construct()
    {
        date_default_timezone_set(TIME_ZONE);
        $this->view = new View();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($this->isAuthorized()) {

            require_once 'application/models/model_user.php';
            $user_model = new Model_User();
            $user = $user_model->get_user_by_id($_SESSION['user_id']);

            if (time() - $_SESSION['last_activity'] > 30*60) {
                $this->showLoginPage('Истёк срок дейсвия сессии!');
            }
            if (session_id() != $user['user_hash']) {
                $this->showLoginPage('Не актуальная сессия!');
            }
            if ($_SERVER['REMOTE_ADDR'] != $user['user_ip']) {
                $this->showLoginPage('Сменился IP адрес!');
            }

        } else {
            if ($_SERVER['REQUEST_URI'] != '/user/login') {
                $this->showLoginPage();
            }
        }
        $_SESSION['last_activity'] = time();
    }

    public function prepare_files($files_arr){

        $allow_types = 'image/jpeg,image/jpg,image/png,image/gif';
        $current_file_types = explode(',',$allow_types);
        $result['result'] = true;

        if(!empty($files_arr)){
            foreach($files_arr as $file_id => $file_item){

                $current_file_ext_arr = array();

                foreach($current_file_types as $ext){
                    $ext_a = explode('/', $ext);
                    $cur_ext = ($ext_a[1]=='jpeg')?"jpeg,jpg":$ext_a[1];
                    $current_file_ext_arr[] = $cur_ext;
                }

                $current_file_ext = implode(', ',$current_file_ext_arr);

                if(!empty($files_arr[$file_id]['name'])){
                    $name = $files_arr[$file_id]['name'];
                    $type = $files_arr[$file_id]['type'];
                    $size = $files_arr[$file_id]['size'];
                    $tmp_name = $files_arr[$file_id]['tmp_name'];
                    $error = $files_arr[$file_id]['error'];

                    if(!empty($name)){
                        $temp_ar_name = explode('.', $name);
                        $exist = end($temp_ar_name);
                        $md5_name = md5_file($tmp_name).".".$exist;


                        if($error !=4 && $error !=0){
                            $result["result"] = false;
                            $result["result_msg"] = "Ошибка при работе в файлом";
                        }else{
                            if (array_search($type,$current_file_types)===false) {
                                $result["result"] = false;
                                $result["result_msg"] = "Недопустимое расширение";
                            } elseif ($size >= 10000000) {
                                $result["result"] = false;
                                $result["result_msg"] = "Превышен размер файла";
                            } else {
                                $dir_name = "images/events";
                                $path = "/$dir_name/";
                                $move_files = $_SERVER['DOCUMENT_ROOT']."/$dir_name/$md5_name";
                                $move_status = move_uploaded_file($tmp_name, $move_files);
                                if (!$move_status) {
                                    $result["result"] = false;
                                    $result["result_msg"] = "Ошибка записи в папку";
                                } else {

                                    $result[$file_id]["event_img_md5"]=$md5_name;
                                    $result[$file_id]["event_img_name"]=$name;
                                    $result[$file_id]["event_img_path"]=$path;
                                }
                            }
                        }
                    }
                }
                unset($name);
                unset($type);
                unset($size);
                unset($tmp_name);
                unset($error);
            }
        }
        return $result;
    }


    public  function simple_clear($str){
        $str = preg_replace("/(<\?=|<\?php|<script|<\?xml)/", "", $str);
        return $str;
    }
    public function redirect($section){
        if(!empty($section)){
            $url = "http://".$_SERVER['HTTP_HOST']."/".$section;
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: $url");
        }
        else{
            Route::ErrorPage404();
        }

    }

    protected function isAuthorized() {
        return isset($_SESSION['authorized']) && $_SESSION['authorized'] == 1;
    }

    private function showLoginPage($error = null) {
        if ($error != null) {
            $_SESSION['error'] = $error;
        }
        $_SESSION['authorized'] = 0;
        $this->redirect('user/login');
        exit();
    }

}
