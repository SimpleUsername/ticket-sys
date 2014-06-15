<?php

class Model_Events extends Model
{
    public $table = "events";
    public $sub_table = "event_status";

	public function get_all_events($status = true)
	{
        if($status){
            $result = $this->db->sql("SELECT * FROM `{$this->table}` WHERE `{$this->sub_table}` > -1");

            if(!$result){
                $result = array('msg' => 'Событий  не существует');
            }
            return  $result;
        }else{
            $result = $this->db->sql("SELECT * FROM `{$this->sub_table}`");

            if(!$result){
                $result = array('msg' => 'Статусов для событий  не существует');
            }
            return  $result;
        }

		
	}



}
