<?php
namespace application\controllers;

use application\core\Controller;
use application\core\Model;
use application\models\Model_Config;

class Controller_Config extends Controller
{
    /* @var $model Model_Config */
    private $model;

    public  function __construct(Model $model)
    {
        $this->model = $model;
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
