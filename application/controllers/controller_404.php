<?php
namespace application\controllers;

use application\core\Controller;
use application\core\View;

class Controller_404 extends Controller
{
    /* @var $view View */
    protected $view;

	public function action_index()
	{
		$this->view->generate('404_view.php', 'template_view.php');
	}

}
