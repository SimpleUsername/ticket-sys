<?php

class Model_Events extends Model
{

    private  $_table = "events";
    private $_sub_table = "event_status";

    public function get_all_events($status = true, array $statuses = array(0,1,2,3))
    {
        if($status){
            $result = $this->db->sql("SELECT ev.*, STR_TO_DATE(ev.event_date, '%d.%m.%Y %H:%i') ev_date, evs.estatus_name,
                                        sum(case when t.ticket_type = 'reserved' then 1 else 0 end) reserved_count,
                                        sum(case when t.ticket_type = 'purchased' then 1 else 0 end) purchased_count,
                                        fc.free_count
                                        FROM `{$this->_table}` as ev
                                        LEFT JOIN `event_status` as evs
                                        ON ev.event_status = evs.estatus_id
                                        LEFT JOIN tickets as t
                                        ON ev.event_id = t.event_id
                                        JOIN (select event_id, sum(free_count) free_count from tickets_count group by event_id) fc
                                        ON ev.event_id = fc.event_id
                                        WHERE `event_status` IN (".implode(", ", $statuses).") GROUP BY ev.event_id ORDER BY ev_date DESC");

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
}