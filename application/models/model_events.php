<?php

class Model_Events extends Model
{

	public function get_all_events($status = true)
	{
        if($status){
            $result = $this->db->sql('SELECT * FROM `events` WHERE `event_status` > -1');

            if(!$result){
                $result = ['msg' => 'Событий  не существует'];
            }
            return  $result;
        }else{
            $result = $this->db->sql('SELECT * FROM `events` WHERE `event_status` > -1');

            if(!$result){
                $result = ['msg' => 'Событий  не существует'];
            }
            return  $result;
        }

		
	}



}
