<?php
namespace fragments\web;

class Environment{

    public $name;
    public $dir;

    public function __construct(){
        if(STATE == 'development'){
            $host = $_SERVER['HTTP_HOST'];
            $host = (substr_count($host,  '.') == 1) ? \Frag::$app->defaultEnvironment : substr($host, 0, strpos($host, '.'));
        } else {
            $host = $_SERVER['HTTP_HOST'];
            if(strpos($host, '.') !== false){
                $host = substr($host, 0, strpos($host, '.'));
            } else {
                $host = \Frag::$app->defaultEnvironment;
            }
        }
        $this->name = $host;
        $this->dir = "$host/";
    }
}
?>
