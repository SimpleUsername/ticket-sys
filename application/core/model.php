<?php

class Model
{
	
	public function __construct(){
        $this->db = new Db();
    }

	public function get_data()
	{
		// todo
	}

    public function insert($table, $fields){
        $result =$this->db->insert($table,$fields);
        return $result;
    }
}