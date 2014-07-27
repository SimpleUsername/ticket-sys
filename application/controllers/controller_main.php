<?php

class Controller_Main extends Controller
{

	function action_index()
	{
        if ($_SESSION['user_seller'] || $_SESSION['user_manager']) {
            $this->redirect("events");
        } elseif ($_SESSION['user_admin']) {
            $this->redirect("users");
        }
	}
}