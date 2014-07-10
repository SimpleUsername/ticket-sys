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
        if (!$_SESSION['user_seller']) {
            $this->redirect('404');
        }
    }
    public function action_sell($event_id) {
        $data = $this->model->get_event_by_id($event_id);
        date_default_timezone_set('Europe/Kiev');
        $current_date = time();
        $event_booking_end = strtotime($data['event_booking_end']);
        if ($event_booking_end < $current_date) {
            $this->model->delete_order($data['event_id'], null, 'reserved');
        }
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
        $data['role'] = "sell";
        $data['sectors'] = $sectors;
        $data['title'] = "Продажа билета на ".$data['event_name']." (".$data['event_date'].")";
        $this->view->generate('tickets_choose_modal_view.php', 'template_modal_view.php', $data);
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
        $this->view->generate('tickets_success_modal_view.php', 'template_modal_view.php', $data);
    }
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
    public function action_reserve($event_id) {
        if (empty($_POST)) {
            $data = $this->model->get_event_by_id($event_id);
            $data['title'] = "Бронирование билетов на ".$data['event_name']." (".$data['event_date'].")";
            $this->view->generate('tickets_customer_modal_view.php', 'template_modal_view.php', $data);
        } else {
            //Step 2
            $data = $this->model->get_event_by_id($event_id);
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
            $data['role'] = "reserve";
            $data['sectors'] = $sectors;
            $data['customer_id'] = $_POST['customer_id'];
            $data['title'] = "Бронирование билетов на ".$data['event_name']." (".$data['event_date'].")";
            $this->view->generate('tickets_choose_modal_view.php', 'template_modal_view.php', $data);
        }
    }
    public function action_reserveSearch($customer_id = null) {
        if ($customer_id == null) {
            $data['title'] = "Поиск забронированных билетов";
            $this->view->generate('tickets_customer_modal_view.php', 'template_modal_view.php', $data);
        } else {
            $data['role'] = "unreserve";
            $data['customer'] = end($this->model->get_customer_by_id((int)$customer_id));
            $data['tickets'] = $this->model->get_reserved_tickets((int)$customer_id);

            date_default_timezone_set('Europe/Kiev');
            $current_date = time();

            foreach ($data['tickets'] as $key=>$ticket) {
                $event_sale = strtotime($ticket['event_sale']);
                if ($event_sale > $current_date) {
                    $data['tickets'][$key]['sale_available'] = false;
                } else {
                    $data['tickets'][$key]['sale_available'] = true;
                }
            }
            $data['title'] = "Билеты, забронированные на имя ".$data['customer']['customer_name'];
            $this->view->generate('tickets_reserved_list_modal_view.php', 'template_modal_view.php', $data);
        }
    }
    public function action_reserveSell() {
        $tickets = json_decode($_POST['tickets']);
        $total = 0;
        foreach ($tickets as $key=>$ticket) {
            $place = end($this->model->get_place((int)$ticket->placeId));
            $event = $this->model->get_event_by_id((int)$ticket->eventId);

            $this->model->set_ticket_type($ticket->eventId, $ticket->placeId, 'purchased');

            $real_ticket = $this->model->get_ticket_by_ids($ticket->eventId, $ticket->placeId);
            $data['tickets'][] = array(
                'ticket_id' => $ticket->eventId."-".$ticket->placeId,
                'event_name' => $event['event_name'],
                'event_date' => $event['event_date'],
                'place_id' => $ticket->placeId,
                'place_no' => $place['place_no'],
                'row_no' => $place['row_no'],
                'sector_id' => $place['sector_id'],
                'price'=> $real_ticket['price']
            );
        }
        $data['total'] = $total;
        $data['role'] = "success";
        $data['title'] = "Выкуп брони билетов на ".$event['event_name']." (".$event['event_date'].")";
        $this->view->generate('tickets_success_modal_view.php', 'template_modal_view.php', $data);
    }

    public function action_search() {
        $data['events'] = $this->model->get_events();
        $data['sectors'] = $this->model->get_sectors();
        $data['title'] = "Поиск билета";
        $this->view->generate('tickets_search_modal_view.php', 'template_modal_view.php', $data);
    }
    public function action_getTicketsManual() {
        echo json_encode($this->model->get_tickets($_POST['event_id'], $_POST['sector_id'], $_POST['row_no'], $_POST['place_no']));
    }
    public function action_getTicketsById() {
        echo json_encode($this->model->get_ticket_by_ids($_POST['event_id'], $_POST['place_no']));
    }
    public function action_getCustomers() {
        $customer_name = $_POST['customer_name'];
        $rows = $this->model->get_customers_by_name($customer_name);
        echo json_encode($rows);
    }
    public function action_addCustomer() {
        $customer_name = htmlspecialchars($_POST['customer_name']);
        $customer_description = htmlspecialchars($_POST['customer_description']);
        $this->model->add_customer($customer_name, $customer_description);
    }
    public function action_changeStatus(){
        $event_id = (int)$_POST['event_id'];
        $place_id = (int)$_POST['place_id'];
        $res = $this->model->delete_order($event_id, $place_id);
    exit(json_encode($res));
    }

    public function action_reserveTickets($event_id) {
        $event = $this->model->get_event_by_id($event_id);
        $prices =  unserialize($event['event_prices']);
        $places = json_decode($_POST['tickets']);
        $customer_id = $_POST['customer_id'];
        $total = 0;
        $data['tickets'] = array();
        foreach ($places as $key=>$place_id) {
            $place = $this->model->get_place($place_id)[0];

            foreach ($prices as $key=>$sector) {
                if ($sector['sector_id'] == $place['sector_id']) {
                    $error = false;
                    try {
                        $this->model->add_ticket($event_id, $place_id, 'reserved', $customer_id, $sector['sector_price']);
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
        $data['title'] = "Бронирование билетов на ".$event['event_name']." (".$event['event_date'].")";
        $this->view->generate('tickets_success_modal_view.php', 'template_modal_view.php', $data);
    }
}
