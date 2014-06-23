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
    public function select($table,$where = null , $params = null){
        $result = $this->db->insert($table,$where , $params);
        return $result;
    }
    public function insert($table, $fields){
        $result =$this->db->insert($table,$fields);
        return $result;
    }
    public function  update($table, $fields, $where, $params=null){
        $result = $this->db->update($table, $fields, $where, $params);
        return $result;
    }
    public function  delete($table, $where, $params=null){
        $result = $this->db->delete($table, $where, $params);
        return $result;
    }
}