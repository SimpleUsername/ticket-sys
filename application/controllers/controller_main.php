<?php
namespace application\controllers;

use application\core\Authority;
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
        if (Authority::isA(User::MANAGER | User::SELLER)) {
            Route::redirect("events");
        } elseif (Authority::isA(User::ADMIN)) {
            Route::redirect("users");
        }
	}
}