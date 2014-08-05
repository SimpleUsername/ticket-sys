<?php
namespace application\core;

use Conf;
use application\core\View;

class Route
{
    static function start()
    {
        $controller_name = 'Main';
        $action_name = 'index';
        $routes = explode('/', $_SERVER['REQUEST_URI']);

        if ( !empty($routes[1]) )
        {
            $controller_name = ucfirst($routes[1]);
        }

        if ( !empty($routes[2]) )
        {
            $action_name = $routes[2];
        }

        if( !empty($routes[3])){
            $arg[] = intval($routes[3]);
        }

        $model_name = '\application\models\Model_'.$controller_name;
        $controller_name = '\application\controllers\Controller_'.$controller_name;
        $action_name = 'action_'.$action_name;

        if (0) {
            echo "Model: $model_name <br>".PHP_EOL;
            echo "Controller: $controller_name <br>".PHP_EOL;
            echo "Action: $action_name <br>".PHP_EOL;
        }

        if(class_exists($model_name))
        {
            $model = new $model_name(new Db());
        }
        if(class_exists($controller_name))
        {
            $controller = new $controller_name($model, new View());
        }
        else
        {
            Route::ErrorPage404();
        }

        $action_name;

        if(method_exists($controller, $action_name))
        {
            if(!empty($routes[3])){
                call_user_func_array(array($controller,$action_name) , $arg);

            }else{
                $controller->$action_name();
            }
        }
        else
        {
            Route::ErrorPage404();
        }
    }

    public static function ErrorPage404()
    {
        header('HTTP/1.1 404 Not Found');
        $controller = new \application\controllers\Controller_404();
        $controller->action_index();
        exit();
    }
}
