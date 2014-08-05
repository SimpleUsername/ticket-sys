<?php
namespace application\controllers;

use application\core\Controller;
use application\core\Route;
use application\entity\User;

class Controller_Main extends Controller
{

    public function getAcceptedUserType()
    {
        return User::SELLER | User::MANAGER | User::ADMIN;
    }
	function action_index()
	{
        if ($this->session['user_type'] & (User::MANAGER | User::SELLER)) {
            Route::redirect("events");
        } elseif ($this->session['user_type'] & (User::ADMIN)) {
            Route::redirect("users");
        }
	}
}