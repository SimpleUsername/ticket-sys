<?php
namespace application\core;

use application\controllers\Controller_404;
use application\models\Model_Users;
use Conf;
use application\core\View;

class Route
{
    private static $_DB;

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

        self::$_DB = new DB();

        if(class_exists($model_name))
        {
            $model = new $model_name(self::$_DB);
        }
        if(class_exists($controller_name))
        {
            /* @var $controller \application\core\Controller */
            $controller = new $controller_name();
            if (isset($model)) {
                $controller->setModel($model);
            }
            $controller->setView(new View());
            $controller->setSession(Session::getInstance());
            self::checkAuthority($controller);
        }
        else
        {
            Route::ErrorPage404();
        }

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

    public static function redirect($section){
        if(!empty($section)) {
            $url = "http://".$_SERVER['HTTP_HOST']."/".$section;
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: $url");
            exit();
        } else {
            self::ErrorPage404();
        }

    }

    public static function ErrorPage404()
    {
        header('HTTP/1.1 404 Not Found');
        $controller = new Controller_404();
        $controller->setView(new View());
        $controller->action_index();
        exit();
    }

    private function checkAuthority(Controller $controller)
    {
        $authority = new Authority();
        $authority->setSession(Session::getInstance());
        $authority->setModel(new Model_Users(self::$_DB));
        $authority->setAcceptedUserType($controller->getAcceptedUserType());
        $authority->checkAuthority();
    }
}
