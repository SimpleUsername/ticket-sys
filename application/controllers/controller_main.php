<?php

class Controller_Main extends Controller
{

	function action_index()
	{
        switch ($_SESSION['user_type_id']) {
            case 1 : $this->redirect("users"); break;
            case 2 : $this->redirect("events"); break;
            case 3 : $this->redirect("events"); break;
        }
	}
}