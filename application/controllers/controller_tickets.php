<?php
namespace application\controllers;

use application\core\Session;
use application\entity\User;
use application\core\Route;
use application\core\Controller;
use application\core\View;
use application\models\Model_Tickets;

class Controller_Tickets extends Controller
{
    /* @var $model Model_Tickets */
    protected $model;
    /* @var $view View */
    protected $view;
    /* @var $session Session */
    protected $session;

    public function getAcceptedUserType()
    {
        return User::SELLER;
    }

    /* shows tickets sale dialog (place pick) */
    public function action_sell($event_id) {

        $data = $this->model->get_event_by_id($event_id);
        if (!$this->isPurchaseAvailable($data)) {
            Route::ErrorPage404();
        }
        $this->check_and_delete_reserve($data);

        $sectors = $this->concatenateSectorAndCounters(unserialize($data['event_prices']),
            $this->model->get_free_places_count($event_id));

        $data['role'] = "sell";
        $data['sectors'] = $sectors;
        $data['title'] = "Продажа билета на ".$data['event_name']." (".$data['event_date'].")";
        $this->view->generate('tickets_choose_modal_view.php', 'template_modal_view.php', $data);
    }

    /* Modal */
    public function action_reserve($event_id) {
        $data = $this->getEventByID($event_id);
        $data['title'] = "Бронирование билетов на ".$data['event_name']." (".$data['event_date'].")";
        if (empty($_POST)) {
            //Step 1
            //Request customer name and reserve description
            $data['role'] = "new-reserve-info";
            $this->view->generate('tickets_new_reserve_modal_view.php', 'template_modal_view.php', $data);
        } else {
            //Step 2
            //Show place pick dialog
            $sectors = $this->concatenateSectorAndCounters(unserialize($data['event_prices']),
                $this->model->get_free_places_count($event_id));
            $data['role'] = "reserve";
            $data['sectors'] = $sectors;
            $data['customer_name'] = htmlspecialchars($_POST['customer_name']);
            $data['reserve_description'] = htmlspecialchars($_POST['reserve_description']);
            $this->view->generate('tickets_choose_modal_view.php', 'template_modal_view.php', $data);
        }
    }

    /* Modal */
    public function action_reserveSearch($reserve_id = null) {
        if ($reserve_id == null) {
            //Step 1. Find reserve by customer name, reserve date etc
            $data['current_date'] = date("d.m.Y");
            $data['events'] = $this->model->get_events();
            $data['title'] = "Поиск забронированных билетов";
            $this->view->generate('tickets_reserve_search_modal_view.php', 'template_modal_view.php', $data);
        } else {
            //Step 2. Show reserved tickets
            $data['role'] = "search_reserve";
            $data['reserve'] = $this->model->get_reserve_by_id((int)$reserve_id);
            $data['tickets'] = $this->model->get_reserved_tickets((int)$reserve_id);
            if (!$data['reserve'] || !$data['tickets']) {
                Route::ErrorPage404();
                exit();
            }
            $event = $this->model->get_event_by_id($data['tickets'][0]['event_id']);
            $data['event'] = $event;
            $current_date = time();
            $event_sale = strtotime($event['event_sale']);
            $data['sale_available'] = $event_sale < $current_date;
            $data['title'] = "Билеты, забронированные на имя ".$data['reserve'][0]['customer_name'];
            $this->view->generate('tickets_reserved_list_modal_view.php', 'template_modal_view.php', $data);
        }
    }

    public function action_reserveSell() {
        $tickets = json_decode($_POST['tickets']);
        $total = 0;
        foreach ($tickets as $ticket) {
            $place = end($this->model->get_place((int)$ticket->placeID));
            $event = $this->model->get_event_by_id((int)$ticket->eventID);
            if (!$this->isPurchaseAvailable($event)) {
                Route::ErrorPage404();
            }
            $this->model->set_ticket_type($ticket->eventID, $ticket->placeID, 'purchased');

            $real_ticket = $this->model->get_ticket_by_ids($ticket->eventID, $ticket->placeID);
            $data['tickets'][] = array(
                'event_name' => $event['event_name'],
                'event_date' => $event['event_date'],
                'place_id' => $real_ticket['place_id'],
                'event_id' => $real_ticket['event_id'],
                'place_no' => $place['place_no'],
                'row_no' => $place['row_no'],
                'sector_id' => $place['sector_id'],
                'price'=> $real_ticket['price']
            );
            $total += $real_ticket['price'];
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

    public function action_pdf($place_id = null){
        require_once "/libs/MPDF56/mpdf.php";
        $place = end($this->model->get_place($place_id ));
        if(!empty($_GET['event_id'])){
            $event_id = (int)$_GET['event_id'];
            $event = $this->model->get_event_by_id($event_id);
            $real_ticket = $this->model->get_ticket_by_ids($event_id, $place_id);
            $data = array(
                'event_name' => $event['event_name'],
                'event_date' => $event['event_date'],
                'place_id' => $place_id,
                'place_no' => $place['place_no'],
                'row_no' => $place['row_no'],
                'sector_id' => $place['sector_id'],
                'price'=> $real_ticket['price']
            );
            $html = '<table>
                <tbody>
                <tr>
                    <td colspan="3"><strong>Название События</strong><br /><br /><br /></td>
                    <td colspan="3"><strong>Дата События</strong></td>
                </tr>
                <tr>
                    <td colspan="3">'.$data['event_name'].'</td>
                    <td colspan="3">'.$data['event_date'].'</td>
                </tr>
                <tr>
                    <td><strong>Сектор:</strong></td>
                    <td>'.$data['sector_id'].'</td>
                    <td><strong>Ряд:</strong></td>
                    <td>'.$data['row_no'].'</td>
                    <td><strong>Место:</strong></td>
                    <td>'.$data['place_no'].'</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Уникальный номер:</strong></td>
                    <td>'.$data['place_id'].'</td>

                    <td colspan="2"><strong>Цена билета:</strong></td>
                    <td>'.$data['price'].'</td>
                </tr>
                </tbody>
            </table>';
            $mpdf = new \mPDF();
            $mpdf->WriteHTML($html);
            $mpdf->Output();
            exit;
        }else{
            Route::redirect('events');
        }

    }

    /* Ajax. Perform ticket sale */
    public function action_createTickets($event_id) {
        $event = $this->getEventByID($event_id);
        $places = json_decode($_POST['tickets']);

        if ($_POST['tickets_type'] == 'reserved') {
            $tickets_type = 'reserved';
            $reserve_id = $this->createReserve($_POST['customer_name'], $_POST['reserve_description']);
            $data['noPDF'] = true;
            $data['title'] = "Бронирование билетов на ".$event['event_name']." (".$event['event_date'].")";
        } else {
            $tickets_type = 'purchased';
            $reserve_id = null;
            $data['title'] = "Продажа билета на ".$event['event_name']." (".$event['event_date'].")";
        }

        if ($this->isPlacesFree($event_id, $places)) {
            $data['tickets'] = array();
            $data['total'] = $this->createTickets($event, $places, $data['tickets'], $tickets_type, $reserve_id);
            $data['role'] = "success";
            $this->view->generate('tickets_invoice_modal_view.php', 'template_modal_view.php', $data);
        } else {
            $data['role'] = "error";
            $data['message'] = "Возникла ошибка! Некоторые места уже заняты!";
            $this->view->generate('error_modal_view.php', 'template_modal_view.php', $data);
        }
    }

    public function action_getTicketsManual() {
        echo json_encode($this->model->get_tickets($_POST['event_id'], $_POST['sector_id'], $_POST['row_no'], $_POST['place_no']));
    }

    public function action_getTicketsById() {
        echo json_encode($this->model->get_ticket_by_ids($_POST['event_id'], $_POST['place_no']));
    }

    public function action_editCustomerName() {
        $this->model->set_customer_name(htmlspecialchars($_POST['reserve_id']), htmlspecialchars($_POST['customer_name']));
    }

    public function action_deleteReserve(){
        $event_id = (int)$_POST['event_id'];
        $place_id = (int)$_POST['place_id'];
        $res = $this->model->delete_order($event_id, $place_id);
        exit(json_encode($res));
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
    /* Ajax */
    // Used by action_reserveSearch. Returns list of reserves, which satisfies the requirements
    public function action_getReserveInfo() {
        $customer_name = $_POST['customer_name'];
        $event_id = $_POST['event_id'];
        $reserve_date = $_POST['reserve_date'];
        echo json_encode($this->model->get_reserve($customer_name, $event_id, $reserve_date));
    }

    /*************************************************************************
     *                        PRIVATE METHODS                                *
     *************************************************************************/

    private function getEventByID($eventID) {
        $event = $this->model->get_event_by_id($eventID);
        if (!$this->isReserveAvailable($event)) {
            Route::ErrorPage404();
            exit();
        } else {
            return $event;
        }
    }

    private function concatenateSectorAndCounters($prices, $free_places) {
        $sectors = array();
        foreach ($prices as $price_value) {
            foreach ($free_places as $place_value) {
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

    private function isPurchaseAvailable(array $event) {
        $available = true;
        //By event status
        if ($event['event_status'] == 1 || $event['event_status'] == 2) {
            $available = false;
        }
        //By date
        $event_date = strtotime($event['event_date']);
        $event_sale = strtotime($event['event_sale']);
        if ($event_date < time()
            || $event_sale > time() ) {
            $available = false;
        }
        return $available;
    }

    private function isReserveAvailable(array $event) {
        $available = true;
        //By event status
        if ($event['event_status'] == 1 || $event['event_status'] == 2) {
            $available = false;
        }
        //By date
        $event_date = strtotime($event['event_date']);
        $event_booking = strtotime($event['event_booking']);
        $event_booking_end = strtotime($event['event_booking_end']);
        if ($event_date < time()
            || $event_booking > time()
            || $event_booking_end < time()) {
            $available = false;
        }
        return $available;
    }
    private function check_and_delete_reserve($event) {
        $current_date = time();
        $event_booking_end = strtotime($event['event_booking_end']);
        if ($event_booking_end < $current_date) {
            $this->model->delete_order($event['event_id'], null, 'reserved');
        }
    }
    private function isPlacesFree($event_id, array $places) {
        $selected_places_are_free = true;
        foreach ($places as $place) {
            if ($this->model->get_ticket_by_ids($event_id, $place)['event_id']) {
                $selected_places_are_free &= false;
            } else {
                $selected_places_are_free &= true;
            }
        }
        return $selected_places_are_free;
    }
    private function createReserve($customer_name, $reserve_description) {
        $current_date = date("d.m.Y G:i:s");
        $reserve_id = $this->model->add_reserve(htmlspecialchars($customer_name), htmlspecialchars($reserve_description), $current_date);
        return $reserve_id;
    }
    private function createTickets($event, $places, array &$tickets = array(), $type='purchased', $reserve_id=null) {
        $total = 0;
        $prices =  unserialize($event['event_prices']);
        foreach ($places as $place_id) {
            $place = $this->model->get_place($place_id);
            if (!count($place)) {
                Route::ErrorPage404();
                exit();
            }
            $place = end($place);
            foreach ($prices as $sector) {
                if ($sector['sector_id'] == $place['sector_id']) {
                    $this->model->add_ticket($event['event_id'], $place_id, $type, $reserve_id, $sector['sector_price']);
                    $total += $sector['sector_price'];
                    $tickets[] = array(
                        'ticket_id' => $event['event_id']."-".$place_id,
                        'event_name' => $event['event_name'],
                        'event_id' => $event['event_id'],
                        'event_date' => $event['event_date'],
                        'place_id' => $place_id,
                        'place_no' => $place['place_no'],
                        'row_no' => $place['row_no'],
                        'sector_id' => $place['sector_id'],
                        'price'=> $sector['sector_price']);
                }
            }
        }
        return $total;
    }
}
