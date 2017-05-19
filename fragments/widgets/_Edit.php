<?php
namespace fragments\widgets;

use fragments\widgets\Widget;
use fragments\helpers\Html;

class Edit extends Widget{

    public $html;

    public $options = [
        'class' => 'edit edit-default',
    ];

    public $value;

    public $configuration = [];

    public function prepare(){
        $this->load();
        $out = $this->begin();
        $out .= $this->toolbar();
        $out .= $this->content();
        $out .= $this->end();

        $this->html = $out;
    }

    public function load(){
        if(!empty($this->configuration)){
            $confClass = $this->configuration;
            $this->configuration = $confClass::options();
        }
    }

    public function run(){
        return $this->html;
    }

    public function toolbar(){
        $out = '';
        if(isset($this->configuration['toolbar'])){
            $out .= "<section class='toolbar'>";
            $out .= "<ul>";
            foreach($this->configuration['toolbar'] as $key => $option){
                if(is_string($option)){
                    $out .= "<li data-action=$key>$option</li>";
                } else {
                    $out .= "<li data-action=$key>$key";
                    $out .= "<ul>";
                    foreach($option as $_key => $_option){
                        $out .= "<li data-action=$_key>$_option</li>";
                    }
                    $out .= "</ul>";
                }

            }
            $out .= "</ul>";
            $out .= "</section>";
        }
        return $out;
    }
    public function begin(){
        return "<section " . Html::constructAttributes($this->options) . ">";
    }
    public function content(){
        $type = isset($this->configuration['type']) ? $this->configuration['type'] : 'plain';
        $out = "";
        if($type == 'plain'){
            $out .= "<textarea>$this->value</textarea>";
        } else if($type == 'html') {
            $out .= "<textarea class='hidden'>$this->value</textarea>";
            $out .= "<div contenteditable='true'>$this->value</div>";
        }
        return $out;
    }
    public function end(){
        return "</section>";
    }
}

?>
