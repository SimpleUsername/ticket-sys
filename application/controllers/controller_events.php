<?php
namespace application\controllers;

use application\core\Controller;
use application\core\Model;
use application\core\View;
use application\core\Route;
use application\core\Session;
use application\entity\User;
use application\entity\Event;
use application\models\Model_Events;

class Controller_Events extends Controller
{
    /* @var $model Model_Events */
    protected $model;
    /* @var $view View */
    protected $view;
    /* @var $session Session */
    protected $session;
/*
    public function __construct()
    {
        $this->check_and_delete_not_sold_reserve();
        $this->check_for_old_events();
    }
*/
    public function getAcceptedUserType()
    {
        return User::SELLER | User::MANAGER;
    }

    public function action_index()
    {
        $data = $this->model->get_all_events(true, array(0,3));
        if (!empty($data)) {
            foreach ($data as $key=>$event) {
                $data[$key]['event_reserve_available'] = $this->event_reserve_available($event);
                $data[$key]['event_purchase_available'] = $this->event_purchase_available($event);
            }
        }
        $this->view->generate('events_view.php', 'template_view.php', $data);
    }

    public function action_archive()
    {
        $data = $this->model->get_all_events(true, array(-1,1,2));
        $this->view->generate('events_archive_view.php', 'template_view.php', $data);
    }
    public function action_recovery($event_id)
    {
        $this->model->recovery_event((int)$event_id);
        Route::redirect('events/archive');
    }

    public function action_add(){

        if(!empty($_POST['event_name'])){
            $form_data =array('event_name' => htmlspecialchars($_POST['event_name']),
                'event_status' => $_POST['event_status'],
                'event_desc' => htmlspecialchars($this->simple_clear($_POST['event_desc'])),
                'event_date' => htmlspecialchars($_POST['event_date']),
                'event_booking' => htmlspecialchars($_POST['event_booking']),
                'event_booking_end' => htmlspecialchars($_POST['event_booking_end']),
                'event_sale' => htmlspecialchars($_POST['event_sale']));
            if(!empty($_FILES['event_img']) && $_FILES['event_img']['error'] != 4){
                $file = $this->prepare_files($_FILES);
                $form_data['event_img_name'] = $file['event_img']['event_img_name'] ;
                $form_data['event_img_md5']  = $file['event_img']['event_img_md5'];
                $form_data['event_img_path'] = $file['event_img']['event_img_path'];
            }
            if(!empty($_POST['sector'])){
                foreach ($_POST['sector'] as $key => $sector) {
                    $_POST['sector'][$key]['sector_price'] = (int)$sector['sector_price'];
                }
                $form_data['event_prices']  = serialize($_POST['sector']);
            }
            $res = $this->model->insert('events', $form_data);
            if(!$res){
                $data['error'] = "Возникла ошибка";
            }else{
                Route::redirect('events');
            }
        }

        $data['disabled_dates'] = array();
        $events = $this->model->get_all_events(array(0,3));
        foreach ($events as $event) {
            $data['disabled_dates'][] = 'moment("'.$event['event_date'].'", "DD.MM.YYYY HH:mm")';
        }

        $data['statuses'] = $this->model->get_all_events(false);
        $data['prices'] = $this->model->get_section_prices();
        $current_date = date("d.m.Y H:i");
        $data['now'] = $current_date;
        $data['event_date'] = $current_date;
        $data['event_booking'] = $current_date;
        $data['event_booking_end'] = $current_date;
        $data['event_sale'] = $current_date;
        $data['action'] = 'add';
        $this->view->generate('events_edit_view.php', 'template_view.php',$data);
    }

    public function action_edit($id){

        if(!empty($_POST['event_id'])){

            $form_data =array('event_name' => htmlspecialchars($_POST['event_name']),
                'event_status' => $_POST['event_status'],
                'event_desc' => htmlspecialchars($this->simple_clear($_POST['event_desc'])),
                'event_date' => htmlspecialchars($_POST['event_date']),
                'event_booking' => htmlspecialchars($_POST['event_booking']),
                'event_booking_end' => htmlspecialchars($_POST['event_booking_end']),
                'event_sale' => htmlspecialchars($_POST['event_sale']));
            if(!empty($_FILES['event_img']) && $_FILES['event_img']['error'] != 4){
                $file = $this->prepare_files($_FILES);
                $form_data['event_img_name'] = $file['event_img']['event_img_name'] ;
                $form_data['event_img_md5']  = $file['event_img']['event_img_md5'];
                $form_data['event_img_path'] = $file['event_img']['event_img_path'];

            }
            if(!empty($_FILES['event_img']) && $_FILES['event_img']['error'] == 4 && !empty($_POST['event_img_md5'])){
                $form_data['event_img_name'] = $_POST['event_img_name'] ;
                $form_data['event_img_md5']  = $_POST['event_img_md5'];
                $form_data['event_img_path'] = $_POST['event_img_path'];
            }
            if(!empty($_POST['sector'])){
                foreach ($_POST['sector'] as $key => $sector) {
                    $_POST['sector'][$key]['sector_price'] = (int)$sector['sector_price'];
                }
                $form_data['event_prices']  = serialize($_POST['sector']);
            }
           $upd = $this->model->update('events',$form_data,' event_id=:event_id ', array(':event_id' => (int)$_POST['event_id']));
            if(!$upd){
                $data['error'] = "Возникла ошибка";
                $res = $this->model->get_event_by_id($id);

                if(isset($res) && count($res) == 1){
                    $data = $res[0];
                }
                $data['statuses'] = $this->model->get_all_events(false);  // получение статусов

                if(is_array($data['event_prices'])){
                    $data['prices'] = unserialize($data['event_prices']);
                }else{
                    $data['prices'] = $this->model->get_section_prices();
                }
                $this->view->generate('events_edit_view.php', 'template_view.php',$data);
            }else{
                Route::redirect('events');
            }
        }else{
            $res = $this->model->get_event_by_id($id);
            if(isset($res) && count($res) == 1){
                $data = $res[0];
            }

            $data['disabled_dates'] = array();
            $events = $this->model->get_all_events(array(0,3));
            foreach ($events as $event) {
                if ($event['event_id'] != $id) {
                    $data['disabled_dates'][] = 'moment("'.$event['event_date'].'", "DD.MM.YYYY HH:mm")';
                }
            }

            $data['statuses'] = $this->model->get_all_events(false);  // получение статусов

            if(!empty($data['event_prices'])){
                $data['prices'] = unserialize($data['event_prices']);
            }else{
                $data['prices'] = $this->model->get_section_prices();
            }
            $data['now'] = date("d.m.Y G:i");
            $data['action'] = 'edit';
            $this->view->generate('events_edit_view.php', 'template_view.php',$data);
        }
    }

    public function action_del($id){
        $id = (int)$id;
        $fields= array('event_status' => -1 );
        $data['result'] = $this->model->update('events',$fields,' event_id=:event_id ', array(':event_id' => $id));
        Route::redirect('events');
    }

    public function action_del_ajax(){
        $data['msg'] = 'Не переданны данные дня удаления';
        if(!empty($_POST['del_id'])){
            $fields= array('event_status' => -1 );
            $data['result'] = $this->model->update('events',$fields,' event_id=:event_id ', array(':event_id' => (int)$_POST['del_id']));
            if($data['result']){
                $data['msg'] = "Событие удалено";
            }
            else{
                $data['msg'] = "Возникла ошибка при удалении";
            }

        }
        exit(json_encode($data));
    }

    public function action_getCountersAndEventStatuses(){
        $events = $this->model->get_all_events();
        $stats = array();
        foreach ($events as $event) {
            $event_stat = array();
            $event_stat['event_reserve_available'] = $this->event_reserve_available($event);
            $event_stat['event_purchase_available'] = $this->event_purchase_available($event);
            $event_stat['free_count'] = (int)$event['free_count'];
            $event_stat['reserved_count'] = (int)$event['reserved_count'];
            $event_stat['purchased_count'] = (int)$event['purchased_count'];
            $stats[$event['event_id']] = $event_stat;
        }
        echo json_encode($stats);
    }

    private function event_purchase_available(array $event) {
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
        //By free places
        if ($event['free_count'] == 0 ) {
            $available = false;
        }
        return $available;
    }
    private function event_reserve_available(array $event) {
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
        //By free places
        if ($event['free_count'] == 0 ) {
            $available = false;
        }
        return $available;
    }

    private function check_and_delete_not_sold_reserve() {
        $current_date = time();
        $actual_events = $this->model->get_all_events(array(0,3));
        foreach ($actual_events as $event) {
            $event_booking_end = strtotime($event['event_booking_end']);
            if ($event_booking_end < $current_date) {
                $this->model->clear_reserve($event['event_id'], null, 'reserved');
            }
        }
    }
    private function check_for_old_events() {
        $current_date = time();
        $actual_events = $this->model->get_all_events(array(0,3));
        foreach ($actual_events as $event) {
            $event_date = strtotime($event['event_date']);
            if ($event_date < $current_date) {
                $this->model->set_event_status($event['event_id'], 1);
            }
        }
    }

}
