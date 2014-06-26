<?php
/**
 * Created by PhpStorm.
 * User: Ilia
 * Date: 26.06.14
 * Time: 1:17
 */
class Controller_Seller extends Controller {
    public function __construct()
    {
        $this->model = new Model_Seller();
        parent::__construct();
        if ($_SESSION['user_type_id'] != 3) {
            $this->redirect('404');
        }
    }
    public function action_index() {
        //TODO implement me :3
    }
}
