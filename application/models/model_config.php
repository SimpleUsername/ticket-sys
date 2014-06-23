<?php

class Model_Config extends Model
{
    public $table_section = "sector";

    public function get_section_prices()
    {
        $result = $this->db->sql("SELECT * FROM `{$this->table_section}` ");

        if(!$result){
            $result['msg'] = 'Сектора  не заполнены';
        }
        return  $result;
    }
}
