<?php
/**
 * Created by PhpStorm.
 * User: Ilia
 * Date: 26.06.14
 * Time: 1:17
 */
class Controller_Tickets extends Controller {
    public function __construct()
    {
        $this->model = new Model_Tickets();
        parent::__construct();
        if ($_SESSION['user_type_id'] != 3) {
            $this->redirect('404');
        }
    }
    public function action_index() {
        //TODO implement me :3
    }
    public function action_sale() {
        if (empty($_POST)) {
            $this->view->generate('tickets_sale_view.php', 'template_view.php');
        }
    }
    /*ajax methods*/
    public function action_ajaxGetEventsByName() {
        $event_name = "";
        $events = $this->model->get_events_by_name($event_name);
        echo json_encode($events);
    }
}
