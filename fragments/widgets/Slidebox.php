<?php
namespace fragments\widgets;

use fragments\widgets\Widget;
use fragments\helpers\Html;

class Slidebox extends Widget{
    public $options = [
        'class' => 'slidebox slidebox-default',
    ];
    public $inputOptions = [];
    public $value;

    public $html;

    public $trueText = 'ON';
    public $falseText = 'OFF';

    public function prepare(){


        $state = ($this->value == true) ? 'true' : 'false';
        $this->options['class'] = (!isset($this->options['class']) ? " $state" : $this->options['class'] . " $state");
        $this->inputOptions['type'] = 'checkbox';
        unset($this->inputOptions['value']);
        if($this->value == true){
            $this->inputOptions['checked'] = '';
        }

        $output = "<div ". Html::constructAttributes($this->options) . '>';
            $output .= "<input " . Html::constructAttributes($this->inputOptions) . ">";
            $output .= "<label for='".$this->inputOptions['id']."'>";
            $output .= "<div class='toggler'>";
                $output .= "<div class='left'>$this->trueText</div>";
                $output .= "<div class='center'></div>";
                $output .= "<div class='right'>$this->falseText</div>";
            $output .= "</div>";
        $output .= "</div>";

        $this->html = $output;
    }
    public function run(){
        return $this->html;
    }
}

?>
