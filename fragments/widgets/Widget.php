<?php
namespace fragments\widgets;

class Widget{

    public function __construct($params = []){
        foreach($params as $key => $value){
            $this->$key = $value;
        }
    }


    public static function widget($params = []){
        $c = get_called_class();

        $widget = new $c($params);
        $widget->prepare();
        return $widget->run();
    }

    public static function className(){
        return get_called_class();
    }

    public function prepare(){}
    public function run(){}
}
?>
