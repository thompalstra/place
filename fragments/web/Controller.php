<?php
namespace fragments\web;

use fragments\helpers\StringHelper;
use fragments\web\View;
use fragments\web\Response;

class Controller{


    public function __construct(){
        $this->layout = \Frag::$app->defaultLayout;
        $this->response = new Response();
    }

    public static function get($directory){

        $pathInfo = pathinfo($directory);

        $cArray = [];

        $path = \Frag::$app->environment->dir.'controllers/';

        $name = ucwords(StringHelper::toCamelCase($pathInfo['filename']).'Controller');
        $prefix = '';
        if($pathInfo['dirname'] !== '\\'){
             $path .= $prefix = ltrim($pathInfo['dirname'], '/').'/';
        }

        $controllerClass = "\\".str_replace('/', '\\',"$path"."$name");
        if(class_exists($controllerClass)){
            $controller = new $controllerClass();
            $controller->filename = $prefix.$pathInfo['filename'];
            return $controller;
        } else {
            return new \Exception("Controller does not exist: $controllerClass", 404);
        }
    }

    public function runAction($action, $params){
        $a = Action::get($action);
        if(method_exists($this, $a->actionId)){
            $p = [];
            $reflectionMethod = new \ReflectionMethod($this, $a->actionId);
            $requiredParameters = $reflectionMethod->getParameters();
            $params = [];
            if(!empty($requiredParameters)){
                foreach($requiredParameters as $requiredParameter){
                    if(!empty($params) && isset($params[$requiredParameter])){
                        $p[$requiredParameter->name] = $params[$requiredParameter->name];
                    } else if(!empty($_GET) && isset($_GET[$requiredParameter->name])){
                        $p[$requiredParameter->name] = $_GET[$requiredParameter->name];
                    }
                }
            }
            return call_user_func_array(array($this, $a->actionId), $p);
        }
        return new \Exception("Action does not exists $a->actionId", 404);
    }

    public function runError($exception){
        http_response_code($exception->getCode());
        $pathInfo = pathinfo(\Frag::$app->errorRoute);

        $c = Controller::get($pathInfo['dirname']);
        $a = Action::get($pathInfo['filename']);
        \Frag::$app->controller = $c;
        \Frag::$app->actionId = $a;

        if(method_exists(\Frag::$app->controller, $a->actionId)){
            return call_user_func_array(array(\Frag::$app->controller, $a->actionId), ['exception' => $exception]);
        }
        return new \Exception("Action does not exists $a->actionId", 404);
    }


    public function render($view, $params = []){
        \Frag::$app->view = View::get($view);
        if(get_class(\Frag::$app->view) !== 'Exception'){
            return \Frag::$app->view->render($params);
        }
        return \Frag::$app->view;
    }
    public function renderPartial($view, $params = []){
        \Frag::$app->view = new View();
        return \Frag::$app->view->renderPartial($view, $params);
    }
    public function redirect($url){
        return header("Location: $url");
    }
}
?>
