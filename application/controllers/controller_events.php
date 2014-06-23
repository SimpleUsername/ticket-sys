<?php

class Controller_Events extends Controller
{

    public function __construct()
    {
        $this->model = new Model_Events();
        parent::__construct();
    }

    public function action_index()
    {
        $data = $this->model->get_all_events(true);
        $this->view->generate('events_view.php', 'template_view.php', $data);
    }


    public function action_add(){
        if(!empty($_POST['event_name'])){
            $form_data =array('event_name' => $_POST['event_name'],
                'event_status' => $_POST['event_status'],
                'event_desc' => $_POST['event_desc'],
                'event_date' => $_POST['event_date'],
                'event_booking' => $_POST['event_booking'],
                'event_sale' => $_POST['event_sale']);
            if(!empty($_FILES['event_img'])){
                $file = $this->prepare_files($_FILES);
                $form_data['event_img_name'] = $file['event_img']['event_img_name'] ;
                $form_data['event_img_md5']  = $file['event_img']['event_img_md5'];
                $form_data['event_img_path'] = $file['event_img']['event_img_path'];

            }
            $res = $this->model->insert($this->model->table, $form_data);
            if(!$res){
                $data = $this->model->get_all_events(false);// получение статусов
                $data['error'] = "Возникла ошибка";
                $this->view->generate('events_add_view.php', 'template_view.php',$data);
            }else{
                $this->redirect('events');
            }

        }else{
            $data = $this->model->get_all_events(false); // получение статусов
            $this->view->generate('events_add_view.php', 'template_view.php',$data);

        }
    }

    public function action_edit($id){

        if(!empty($_POST['event_id'])){
            $form_data =array('event_name' => $_POST['event_name'],
                'event_status' => $_POST['event_status'],
                'event_desc' => $_POST['event_desc'],
                'event_date' => $_POST['event_date'],
                'event_booking' => $_POST['event_booking'],
                'event_sale' => $_POST['event_sale']);
            if(!empty($_FILES['event_img']) && $_FILES['event_img']['error'] != 4){
                $file = $this->prepare_files($_FILES);
                $form_data['event_img_name'] = $file['event_img']['event_img_name'] ;
                $form_data['event_img_md5']  = $file['event_img']['event_img_md5'];
                $form_data['event_img_path'] = $file['event_img']['event_img_path'];

            }
            if(!empty($_FILES['event_img']) && $_FILES['event_img']['error'] == 4 && !empty($_POST['event_img_md5'])){
                $form_data['event_img_name'] = $_POST['event_img_name'] ;
                $form_data['event_img_md5']  = $_POST['event_img_md5'];
                $form_data['event_img_path'] = $_POST['event_img_path'];
            }

            $upd = $this->model->update('events',$form_data,' event_id=:event_id ', array(':event_id' => (int)$_POST['event_id']));
            if(!$upd){
                $data['error'] = "Возникла ошибка";
                $res = $this->model->get_event_by_id($id);

                if(isset($res) && count($res) == 1){
                    $data = $res[0];
                }
                $data['statuses'] = $this->model->get_all_events(false);  // получение статусов
                $this->view->generate('events_edit_view.php', 'template_view.php',$data);

            }else{
//               print_r($_FILES);exit;
                $this->redirect('events');
            }

        }else{
            $res = $this->model->get_event_by_id($id);

            if(isset($res) && count($res) == 1){
                $data = $res[0];
            }
            $data['statuses'] = $this->model->get_all_events(false);  // получение статусов
            $this->view->generate('events_edit_view.php', 'template_view.php',$data);
        }

    }

    public function action_del($id){
        $id = (int)$id;
        $fields= array('event_status' => -1 );
        $data['result'] = $this->model->update('events',$fields,' event_id=:event_id ', array(':event_id' => $id));
        $this->redirect('events');
    }

    public function action_del_ajax(){
        $data['msg'] = 'Не переданны данные дня удаления';
        if(!empty($_POST['del_id'])){
            $fields= array('event_status' => -1 );
            $data['result'] = $this->model->update('events',$fields,' event_id=:event_id ', array(':event_id' => (int)$_POST['del_id']));
            if($data['result']){
                $data['msg'] = "Событие удалено";
            }
            else{
                $data['msg'] = "Возникла ошибка при удалении";
            }

        }
        exit(json_encode($data));
    }
}
