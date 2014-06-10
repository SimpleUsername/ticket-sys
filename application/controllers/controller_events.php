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
		$data = $this->model->get_data();		
		$this->view->generate('events_view.php', 'template_view.php', $data);
	}
}
