<?php
namespace fragments\web;

use fragments\helpers\StringHelper;

class Request{

    public $isAjax = false;

    public function __construct(){
        foreach((object)$_SERVER as $k => $v){
            $k = StringHelper::toCamelCase($k);
            $this->$k = $v;
        }
        \Frag::$app->url = $_SERVER['REQUEST_URI'];
        $parse = parse_url(\Frag::$app->url);

        if(isset($parse['query'])){
            parse_str($parse['query'], $parameters);
        } else {
            $parameters = [];
        }


        $_GET = $_GET + $parameters;

        \Frag::$app->method = $_SERVER['REQUEST_METHOD'];
        $this->isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']);
    }

    public function getStatus(){
        return http_response_code();
    }
    public function setStatus($status){
        http_response_code($status);
    }
}
?>
