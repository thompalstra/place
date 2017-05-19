<?php
namespace fragments\widgets;

use fragments\helpers\Html;
use fragments\widgets\Widget;

class FileInput extends Widget{

    public $html;
    public $value;

    public $options = [
        'class' => 'fileinput fileinput-default',
    ];

    public $inputOptions = [
        'type' => 'file',
        'name' => 'fileinput',
    ];

    public $labelOptions = [];
    public $placeholder = 'select a file';

    public function prepare(){

        $options = Html::constructAttributes($this->options);
        $this->labelOptions['for'] = $this->inputOptions['name'];

        $this->inputOptions['class'] = (isset($this->inputOptions['class']) ? $this->inputOptions['class'] . ' hidden' : 'hidden');
        $this->inputOptions['value'] = $this->value;
        $this->inputOptions['data-placeholder'] = $this->placeholder;
        $inputOptions = Html::constructAttributes($this->inputOptions);
        $output = "<div $options>";
        $output .= "<input $inputOptions>";


        $labelOptions = $this->labelOptions;

        $labelOptions = Html::constructAttributes($this->labelOptions);
        $text = (empty($this->value) ? $this->placeholder : $this->value);
        $output .= "<label ".$labelOptions.">$text</label>";
        $output .= "<button class='trigger'>...</button>";
        $output .= "<button class='clear'>x</button>";
        $output .= "</div>";

        $this->html = $output;
    }
    public function run(){
        echo $this->html;
    }
}

?>
