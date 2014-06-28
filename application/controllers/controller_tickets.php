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
    public function action_sell($event_id) {
        if (empty($_POST)) {
            $data = $this->model->get_event_by_id($event_id);
            //achtung
            $free_places = $this->model->get_free_places_count($event_id);
            $prices = unserialize($data['event_prices']);
            $sectors = array();
            foreach ($prices as $price_key=>$price_value) {
                foreach ($free_places as $place_key=>$place_value) {
                    if ($price_value['sector_id'] == $place_value['sector_id']) {
                        $sectors[] = array(
                            'sector_id' => $price_value['sector_id'],
                            'sector_name' => $price_value['sector_name'],
                            'sector_price' => $price_value['sector_price'],
                            'sector_free_count' => $place_value['free_count']
                        );
                    }
                }
            }
            //
            $data['role'] = "sell";
            $data['sectors'] = $sectors;
            $data['title'] = "Продажа билета на ".$data['event_name']." (".$data['event_date'].")";
            $this->view->generate('tickets_sell_modal_view.php', 'template_modal_view.php', $data);
        }
    }
    public function action_sellTickets($event_id) {
        $event = $this->model->get_event_by_id($event_id);
        $prices =  unserialize($event['event_prices']);
        $places = json_decode($_POST['tickets']);
        $total = 0;
        $data['tickets'] = array();
        foreach ($places as $key=>$place_id) {
            $place = $this->model->get_place($place_id)[0];

            foreach ($prices as $key=>$sector) {
                if ($sector['sector_id'] == $place['sector_id']) {
                    $error = false;
                    try {
                        $this->model->add_ticket($event_id, $place_id, 'purchased', null, $sector['sector_price']);
                        $total += $sector['sector_price'];
                    } catch(Exception $e) {
                        $error = true;
                    }
                    $data['tickets'][] = array(
                        'ticket_id' => $event_id."-".$place_id,
                        'event_name' => $event['event_name'],
                        'event_date' => $event['event_date'],
                        'place_id' => $place_id,
                        'place_no' => $place['place_no'],
                        'row_no' => $place['row_no'],
                        'sector_id' => $place['sector_id'],
                        'price'=> $sector['sector_price'],
                        'error'=> $error);
                }
            }
        }
        $data['total'] = $total;
        $data['role'] = "success";
        $data['title'] = "Продажа билета на ".$event['event_name']." (".$event['event_date'].")";
        $this->view->generate('tickets_sell_success_modal_view.php', 'template_modal_view.php', $data);
    }
    /*ajax methods*/
    public function action_getRows() {
        $event_id = (int)$_POST['event_id'];
        $sector_id = (int)$_POST['sector_id'];
        $rows = $this->model->get_free_places_count($event_id, $sector_id);
        echo json_encode($rows);
    }
    public function action_getPlaces() {
        $event_id = (int)$_POST['event_id'];
        $sector_id = (int)$_POST['sector_id'];
        $row_no = (int)$_POST['row_no'];
        $filter = array(
            'event_id' => $event_id,
            'sector_id' => $sector_id,
            'row_no' => $row_no
        );
        $rows = $this->model->get_places($filter);
        echo json_encode($rows);
    }
}
