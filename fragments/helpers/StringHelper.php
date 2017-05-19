<?php
namespace fragments\helpers;

class StringHelper{
    public static function toCamelCase($string){
        $string = strtolower($string);
        $string = str_replace('_', ' ', $string);
        $string = str_replace('-', ' ', $string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);
        $string = lcfirst($string);
        return $string;
    }

    public static function toUrl($string){
        $filter = [' ', '_',','];
        $target = ['-', '-',''];
        $string = strtolower(str_replace($filter, $target, $string));
        $string = urlencode($string);

        return $string;
    }

    public static function prettify($string){
        $filter = ['-', '_'];
        $target = [' ', ' '];

        return ucwords(str_replace($filter, $target, strtolower($string)));
    }

    public static function formEncode($string){
        $filter = [' ','_','[]','[',']'];
        $target = ['-','-','','-',''];

        return strtolower(str_replace($filter, $target, $string));
    }
}
?>
