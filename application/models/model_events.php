<?php
namespace application\models;

use application\core\Model;

class Model_Events extends Model
{

    private  $_table = "events";
    private $_sub_table = "event_status";

    public function get_all_events($status = true, array $statuses = array(0,1,2,3))
    {
        if($status){
            $result = $this->db->sql("SELECT ev.*, STR_TO_DATE(ev.event_date, '%d.%m.%Y %H:%i') ev_date, evs.estatus_name
                                        FROM `{$this->_table}` as ev
                                        LEFT JOIN `event_status` as evs
                                        ON ev.event_status = evs.estatus_id
                                        WHERE `event_status` IN (".implode(", ", $statuses).") GROUP BY ev.event_id ORDER BY ev_date");

            return  $result;
        }else{
            $result = $this->db->sql("SELECT * FROM `{$this->_sub_table}`");

            if(!$result){
                $result['msg'] = 'Статусов для событий  не существует';
            }
            return  $result;
        }
    }
    public function get_event_by_id($id){
        $result = $this->db->sql("SELECT * FROM `{$this->_table}` WHERE `event_id` = $id ");
        if(!$result){
            $result['msg'] = 'Событий  не существует';
        }
        return  $result;
    }
    public function recovery_event($event_id) {
        $fields= array('event_status' => 0 );
        $data['result'] = $this->update('events',$fields,' event_status = -1 AND event_id=:event_id ', array(':event_id' => $event_id));
    }

    public function clear_reserve($event_id) {
        $where = 'event_id = :event_id  AND ticket_type = "reserved"';
        return $this->db->delete('tickets', $where, array(":event_id" => $event_id));
    }
    public function set_event_status($event_id, $status) {
        $where = 'event_id = :event_id';
        return $this->update($this->_table, array("event_status" => $status), $where, array(':event_id' => (int)$event_id));
    }
}