<?php


class Route
{

    static function start()
    {
        // контроллер и действие по умолчанию
        $controller_name = 'Main';
        $action_name = 'index';

        $routes = explode('/', $_SERVER['REQUEST_URI']);

        // получаем имя контроллера
        if ( !empty($routes[1]) )
        {
            $controller_name = ucfirst($routes[1]);
        }

        // получаем имя экшена
        if ( !empty($routes[2]) )
        {
            $action_name = $routes[2];
        }
        // убираем грабли при передаче не числа в аргумент
        if( !empty($routes[3])){
            $arg[] = intval($routes[3]);
        }

        // добавляем префиксы
        $model_name = 'Model_'.$controller_name;
        $controller_name = 'Controller_'.$controller_name;
        $action_name = 'action_'.$action_name;

        if (0) {
            echo "Model: $model_name <br>";
            echo "Controller: $controller_name <br>";
            echo "Action: $action_name <br>";
        }

        // подцепляем файл с классом модели (файла модели может и не быть)

        $model_file = strtolower($model_name).'.php';
        $model_path = "application/models/".$model_file;
        if(file_exists($model_path))
        {
            include $model_path;
            $model = new $model_name(new Db());
        }

        // подцепляем файл с классом контроллера
        $controller_file = strtolower($controller_name).'.php';
        $controller_path = "application/controllers/".$controller_file;
        if(file_exists($controller_path))
        {
            include "application/controllers/".$controller_file;
        }
        else
        {
            Route::ErrorPage404();
        }

        // создаем контроллер
        $controller = new $controller_name($model);
        $action = $action_name;

        if(method_exists($controller, $action))
        {
            if(!empty($routes[3])){
                call_user_func_array(array($controller,$action) , $arg);

            }else{
                $controller->$action();
            }

        }
        else
        {

            Route::ErrorPage404();
        }

    }

    public static function ErrorPage404()
    {
        //$host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        include "application/controllers/controller_404.php";
        $controller = new Controller_404();
        $controller->action_index();
        exit();
        //header("Status: 404 Not Found");
        //header('Location:'.$host.'404');
    }

}
