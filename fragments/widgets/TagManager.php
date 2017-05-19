<?php
namespace fragments\widgets;

use fragments\helpers\Html;

class TagManager extends Widget{

    public $value;
    public $html;

    public $minLength = 4;
    public $maxLength = 25;

    public $options = [
        'class' => 'tagmanager tagmanager-default',
    ];
    public $inputOptions = [
        'type' => 'text',
        'id' => 'tagmanager-input',
        'name' => 'tagmanager'
    ];

    public $itemOptions = [
        'class' => 'tag',
    ];

    public function prepare(){
        unset($this->inputOptions['id']);
        $this->inputOptions['class'] = isset($this->inputOptions['class']) ? $this->inputOptions['class'] . ' hidden' : 'hidden';

        $out = $this->start();
        $out .= $this->items();
        $out .= $this->input();
        $out .= $this->end();

        $this->html = $out;
    }
    public function run(){
        return $this->html;
    }

    public function start(){

        $this->options['min-length'] = $this->minLength;
        $this->options['max-length'] = $this->maxLength;

        return '<div ' . Html::constructAttributes($this->options) . '>';
    }
    public function items(){
        $out = "<div class='inner'>";

        $templateOptions = $this->itemOptions;

        $templateOptions['template'] = 'true';
        $templateOptions = Html::constructAttributes($templateOptions);

        $inputOptions = $this->inputOptions;
        $inputOptions['value'] = '';
        $inputOptions['disabled'] = true;
        $inputOptions = Html::constructAttributes($inputOptions);

        $options = Html::constructAttributes($this->options);

        $out .= "<span $templateOptions><input $inputOptions><label class='label-value'></label><i class='material-icons remove'>clear</i></span>";
        if(is_array($this->value)){
            foreach($this->value as $key => $value){
                $inputOptions = $this->inputOptions;
                $inputOptions['class'] = 'hidden';
                $inputOptions['value'] = $value;
                $inputOptions = Html::constructAttributes($inputOptions);
                $out .= "<span class='tag'><input $inputOptions><label class='label-value'>$value</label><i class='material-icons remove'>clear</i></span>";
            }
        }

        $out .= "</div>";

        return $out;
    }

    public function input(){
        return "<input type='text'>";
    }

    public function end(){
        return '</div>';
    }
}

?>
