<?php

class Controller_Events extends Controller
{

    function __construct()
    {
        $this->model = new Model_Events();
        $this->view = new View();
    }

    function action_index()
    {
        $data = $this->model->get_all_events(true);
        $this->view->generate('events_view.php', 'template_view.php', $data);
    }


    function action_add(){
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
                $data = $this->model->get_all_events(false);
                $data['error'] = "Возникла ошибка";
                $this->view->generate('events_add_view.php', 'template_view.php',$data);
            }
            $data = $this->model->get_all_events(true);
            $this->view->generate('events_view.php', 'template_view.php',$data);
        }else{
            $data = $this->model->get_all_events(false);
            $this->view->generate('events_add_view.php', 'template_view.php',$data);

        }
    }
}
