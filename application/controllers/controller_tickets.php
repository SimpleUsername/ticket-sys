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
            Route::ErrorPage404();
        }
    }

    /* shows tickets sale dialog (place pick) */
    public function action_sell($event_id) {
        $data = $this->model->get_event_by_id($event_id);

        date_default_timezone_set('Europe/Kiev');
        $current_date = time();
        $event_booking_end = strtotime($data['event_booking_end']);
        if ($event_booking_end < $current_date) {
            $this->model->delete_order($data['event_id'], null, 'reserved');
        }

        $sectors = $this->concatenateSectorAndCounters(unserialize($data['event_prices']),
            $this->model->get_free_places_count($event_id));

        $data['role'] = "sell";
        $data['sectors'] = $sectors;
        $data['title'] = "Продажа билета на ".$data['event_name']." (".$data['event_date'].")";
        $this->view->generate('tickets_choose_modal_view.php', 'template_modal_view.php', $data);
    }
    /* Ajax. Perform ticket sale */
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
        $this->view->generate('tickets_invoice_modal_view.php', 'template_modal_view.php', $data);
    }
    /* Ajax */
    public function action_getRows() {
        $event_id = (int)$_POST['event_id'];
        $sector_id = (int)$_POST['sector_id'];
        $rows = $this->model->get_free_places_count($event_id, $sector_id);
        echo json_encode($rows);
    }
    /* Ajax */
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

    /* Modal */
    public function action_reserve($event_id) {
        if (empty($_POST)) {
            //Step 1
            //Request customer name and reserve description
            $data = $this->model->get_event_by_id($event_id);
            $data['title'] = "Бронирование билетов на ".$data['event_name']." (".$data['event_date'].")";
            $data['role'] = "new-reserve-info";
            $this->view->generate('tickets_new_reserve_modal_view.php', 'template_modal_view.php', $data);
        } else {
            //Step 2
            //Now we need to show place pick dialog
            $data = $this->model->get_event_by_id($event_id);
            $sectors = $this->concatenateSectorAndCounters(unserialize($data['event_prices']),
                $this->model->get_free_places_count($event_id));
            $data['role'] = "reserve";
            $data['sectors'] = $sectors;
            $data['customer_name'] = htmlspecialchars($_POST['customer_name']);
            $data['reserve_description'] = htmlspecialchars($_POST['reserve_description']);
            $data['title'] = "Бронирование билетов на ".$data['event_name']." (".$data['event_date'].")";
            $this->view->generate('tickets_choose_modal_view.php', 'template_modal_view.php', $data);
        }
    }
    /* Modal */
    public function action_reserveTickets($event_id) {
        $event = $this->model->get_event_by_id($event_id);
        $prices =  unserialize($event['event_prices']);
        $places = json_decode($_POST['tickets']);

        //Check, if chosen places are free
        $all_places_is_free = true;
        foreach ($places as $place) {
            if (!$this->model->get_ticket_by_ids($event_id, $place)) {
                $all_places_is_free &= true;
            } else {
                $all_places_is_free = false;
            }
        }

        if ($all_places_is_free) {
            //Save reserve information
            date_default_timezone_set(TIME_ZONE);
            $current_date = date("d.m.Y G:i:s");
            $customer_name = htmlspecialchars($_POST['customer_name']);
            $reserve_description = htmlspecialchars($_POST['reserve_description']);
            $reserve_id = $this->model->add_reserve($customer_name, $reserve_description, $current_date);

            //Create tickets and generate invoice data
            $total = 0;
            $data['tickets'] = array();
            foreach ($places as $place_id) {
                $place = end($this->model->get_place($place_id));
                foreach ($prices as $sector) {
                    if ($sector['sector_id'] == $place['sector_id']) {
                        $this->model->add_ticket($event_id, $place_id, 'reserved', $reserve_id, $sector['sector_price']);
                        $total += $sector['sector_price'];
                        $data['tickets'][] = array(
                            'ticket_id' => $event_id."-".$place_id,
                            'event_name' => $event['event_name'],
                            'event_date' => $event['event_date'],
                            'place_id' => $place_id,
                            'place_no' => $place['place_no'],
                            'row_no' => $place['row_no'],
                            'sector_id' => $place['sector_id'],
                            'price'=> $sector['sector_price']);
                    }
                }
            }
            $data['total'] = $total;
            $data['role'] = "success";
            $data['title'] = "Бронирование билетов на ".$event['event_name']." (".$event['event_date'].")";
            $this->view->generate('tickets_invoice_modal_view.php', 'template_modal_view.php', $data);
        } else {
            $data['role'] = "error";
            $data['title'] = "Бронирование билетов на ".$event['event_name']." (".$event['event_date'].")";
            $data['message'] = "Возникла ошибка! Некоторые места уже заняты!";
            $this->view->generate('error_modal_view.php', 'template_modal_view.php', $data);
        }
    }
    /* Modal */
    public function action_reserveSearch($reserve_id = null) {
        if ($reserve_id == null) {
            date_default_timezone_set(TIME_ZONE);
            $data['current_date'] = date("d.m.Y");
            $data['title'] = "Поиск забронированных билетов";
            $this->view->generate('tickets_reserve_search_modal_view.php', 'template_modal_view.php', $data);
        } else {
            $data['role'] = "search_reserve";
            $data['reserve'] = end($this->model->get_reserve_by_id((int)$reserve_id));
            $data['tickets'] = $this->model->get_reserved_tickets((int)$reserve_id);

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
            $data['title'] = "Билеты, забронированные на имя ".$data['reserve']['customer_name'];
            $this->view->generate('tickets_reserved_list_modal_view.php', 'template_modal_view.php', $data);
        }
    }
    /* Ajax */
    public function action_getReserveInfo() {
        $custumer_name = $_POST['customer_name'];
        $reserve_date = $_POST['reserve_date'];
        echo json_encode($this->model->get_reserve($custumer_name, $reserve_date));
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
        $this->view->generate('tickets_invoice_modal_view.php', 'template_modal_view.php', $data);
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

    public function action_changeStatus(){
        $event_id = (int)$_POST['event_id'];
        $place_id = (int)$_POST['place_id'];
        $res = $this->model->delete_order($event_id, $place_id);
    exit(json_encode($res));
    }



    private function concatenateSectorAndCounters($prices, $free_places) {
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
        return $sectors;
    }
}
