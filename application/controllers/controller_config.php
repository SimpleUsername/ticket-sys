<?php

class Controller_Config extends Controller
{

    public  function __construct()
    {
        $this->model = new Model_Config();
        parent::__construct();
    }

    public function action_index()
    {
        $data = $this->model->get_section_prices();
        // $data =  $this->model->query();
        $this->view->generate('config_view.php', 'template_view.php', $data);
    }

    public function action_ajax(){
        $table = 'sector';
        if(!empty($_POST['sector_id'])){
           $id = (int) $_POST['sector_id'];
            $fields= array('sector_price' =>(int) $_POST['sector_price']);

            $data['result'] =$this->model->update($table,$fields,'sector_id=:id', array(':id' => $id));
        }
        else{
            $data['error'] = "Ошибка не передан ИД";
        }
        $data['sector_id'] = $_POST['sector_id'];
        $data['sector_price'] = $_POST['sector_price'];
        $data['success'] = "Таблица обновлена";

        exit(json_encode($data));
    }

}
