<?php
namespace fragments\helpers;

class Html{
    public static function constructAttributes($attributes){
        $result = '';
        if(empty($attributes)){
            return $result;
        }
        else{
            foreach($attributes as $key => $value){
                if(is_array($value)){
                    $result .= self::constructArrayAttributes($key,$value);
                }
                else{
                    $result .= $key."='".$value."' ";
                }
            }
        }
        return $result;
    }

    public static function constructArrayAttributes($key, $array){
        $result = $key."='";
        foreach($array as $newKey => $value){
            $result .= $newKey.":".$value."; ";
        }
        $result .= "'";
        return $result;
    }

    public static function a($content, $link, $options = []){
        $options['href'] = $link;
        return "<a " . Html::constructAttributes($options) . ">$content</a>";
    }

    public static function button($content, $options = []){
        return "<button " . Html::constructAttributes($options) . ">$content</button>";
    }

    public static function selectInput($params){
        $options = $params['options'];
        $value = $params['value'];
        $data = $params['data'];
        $out = "<select " . Html::constructAttributes($options). ">";
        foreach($data as $k => $v){
            $selected = ($k == $value) ? 'selected' : '';
            $out .= "<option $selected value='$k'>$v</option>";
        }
        $out .= "</select>";
        return $out;
    }

    public static function textarea($params){
        $options = $params['options'];
        $value = $params['value'];
        return "<textarea " . Html::constructAttributes($options) . ">$value</textarea>";
    }

    public static function input($params){
        $options = $params['options'];
        return "<input " . Html::constructAttributes($options) . "></input>";
    }
}

?>
