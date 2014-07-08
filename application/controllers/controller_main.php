<?php

class Controller_Main extends Controller
{

	function action_index()
	{
        if ($_SESSION['user_seller']) {
            $this->redirect("events");
            exit();
        }
        if ($_SESSION['user_manager']) {
            $this->redirect("events");
            exit();
        }
        if ($_SESSION['user_admin']) {
            $this->redirect("users");
            exit();
        }
	}
}