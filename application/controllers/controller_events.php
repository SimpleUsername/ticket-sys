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
       // $data =  $this->model->query();
		$this->view->generate('events_view.php', 'template_view.php', $data);
	}


    function action_add(){
        $data['error'] = "";
        $form_data = array();

        if(!empty($_POST['event_name'])){
            $form_data =array('event_name' => $_POST['event_name'],
                                'event_status' => $_POST['event_status'],
                                'event_desc' => $_POST['event_desc'],
                                'event_date' => $_POST['event_date'],
                                'event_booking' => $_POST['event_booking'],
                                'event_sale' => $_POST['event_sale']);

        $res = $this->model->insert($this->model->table, $form_data);
         if(!$res){
             $data['error'] = "Возникла ошибка";
         }

            //todo   редирект на главную после успеха
            $this->view->generate('events_add_view.php', 'template_view.php',$data);
        }else{
            $data = $this->model->get_all_events(false);
            $this->view->generate('events_add_view.php', 'template_view.php',$data);

        }
    }
}
