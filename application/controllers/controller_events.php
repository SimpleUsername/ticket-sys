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
        $status = $this->model->get_all_events(false);
        $this->view->generate('events_add_view.php', 'template_view.php',$status);
    }
}
