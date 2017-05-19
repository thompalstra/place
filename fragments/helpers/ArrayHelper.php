<?php
namespace fragments\helpers;

class ArrayHelper{
    public static function map($array, $key, $value){
        $r = [];
        foreach($array as $k => $v){
            $r[$v->$key] = $v->$value;
        }
        return $r;
    }
}

?>
