<?php

class Controller {

    public $model;
    public $view;

    public function __construct()
    {
        $this->view = new View();
    }

    // действие (action), вызываемое по умолчанию
    public function action_index()
    {
        // todo
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

}
