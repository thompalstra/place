<?php
namespace fragments\widgets;

use fragments\widgets\Widget;
use fragments\helpers\Html;

class BreadCrumb extends Widget{

    public $html = '';

    public $options = [
        'class' => 'breadcrumb breadcrumb-default',
    ];

    public $itemOptions = [
        'class' => 'item',
    ];

    public $seperator = '/';

    public function prepare(){
        $options = Html::constructAttributes($this->options);
        $out = "<ul $options>";
        foreach($this->items as $item){
            $itemOptions = Html::constructAttributes($this->itemOptions);
            $line = "<li $itemOptions>";

            if(isset($item['url'])){
                $url = $item['url'];
                $line .= "<a href='$url'>";
            }
            $line .= $item['label'];
            if(isset($item['url'])){
                $line .= "</a>";
            }

            $line .= "</li>";

            $seperatorOptions = $this->itemOptions;
            $seperatorOptions['class'] = isset($seperatorOptions['class']) ? $seperatorOptions['class'] . ' seperator' : $seperatorOptions['class'];
            $seperatorOptions = Html::constructAttributes($seperatorOptions);
            $line .= "<li $seperatorOptions>";
            $line .= $this->seperator;
            $line .= "</li>";

            $out .= $line;
        }
        $out .= "</ul>";

        $this->html = $out;
    }

    public function run(){
        // var_dump($this->html); die();
        return $this->html;
    }
}

?>
