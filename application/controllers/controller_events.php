<?php

class Controller_Events extends Controller
{

    public function __construct()
    {
        $this->model = new Model_Events();
        $this->view = new View();
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
            }
            header("Location: /events");
        }else{
            $data = $this->model->get_all_events(false); // получение статусов
            $this->view->generate('events_edit_view.php', 'template_view.php',$data);

        }
    }

    public function action_edit($id){
        $data = $this->model->get_event_by_id($id);
        $data['statuses'] = $this->model->get_all_events(false);  // получение статусов
        $this->view->generate('events_add_view.php', 'template_view.php',$data);
    }

    public function action_del(){

        if(!empty($_POST['del_id'])){
            $fields= array('event_status' => -1 );
            $data['result'] = $this->model->update('events',$fields,' event_id=:event_id ', array(':event_id' => (int)$_POST['del_id']));
            $data['msg'] = "Событие удалено";
        }
        else{
            $data['msg'] = "Возникла ошибка при удалении";
        }
        exit(json_encode($data));
    }
}
