<?php
namespace fragments\web;

use fragments\helpers\StringHelper;

class Action{

    public $action;
    public $actionId;

    public static function get($action){
        if(strpos($action, '?') !== false){
            $action = substr($action, 0, strpos($action, '?'));
        }
        $a = new Action();
        $a->action = $action;
        $a->actionId = 'action'.ucwords(StringHelper::toCamelCase($action));
        return $a;
    }
}
?>
