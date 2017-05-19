<?php
namespace fragments\widgets;

use fragments\widgets\Widget;
use fragments\helpers\Html;

class DataView extends Widget{

    public $modelProvider;
    public $output;
    public $viewFile;
    public $ajax = false;
    public $options = [
        'class' => 'dataview',
    ];
    public $itemOptions = [
        'class' => 'item',
    ];

    public function prepare(){
        $output = $this->start();
        $output .= $this->items();
        $output .= $this->end();
        $this->output = $output;
    }

    public function run(){
        echo $this->output;
    }

    public function start(){
        return "<section ".Html::constructAttributes($this->options).">";
    }
    public function end(){
        return "</section>";
    }
    public function items(){
        $output = "";

        foreach($this->modelProvider->getModels() as $model){
            $output .= "<div ".Html::constructAttributes($this->itemOptions).">";
            ob_start();
            require(\Frag::$app->root.$this->viewFile);
            $output .= ob_get_clean();
            $output .= "</div>";
        }
        $output .= ($this->modelProvider !== false && $this->ajax == false ? $this->modelProvider->pagination->createPager() : '');
        $output .= ($this->modelProvider !== false && $this->ajax == true ? $this->modelProvider->pagination->createScroller() : '');
        return $output;
    }
}

?>
