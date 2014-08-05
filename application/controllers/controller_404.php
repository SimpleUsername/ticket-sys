<?php
namespace application\controllers;

use application\core\Controller;
use application\core\View;

class Controller_404 extends Controller
{
    private $_view;

	function action_index()
	{
        $this->_view = new View();
		$this->_view->generate('404_view.php', 'template_view.php');
	}

}
