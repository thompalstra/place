<?php
namespace fragments\widgets;

use fragments\widgets\Widget;
use fragments\helpers\Html;

class Nav extends Widget{

    public $items = [];
    public $output;
    public $options = [
        'class' => 'nav nav-default',
    ];
    public $itemOptions = [
        'class' => 'nav nav-item',
    ];

    public $counter = 0;

    public function prepare(){
        $this->output = $this->begin();
        $this->output .= $this->items($this->items);
        $this->output .= $this->end();
    }

    public function run(){
        return $this->output;
    }

    public function begin(){
        $options = Html::constructAttributes($this->options);
        $output = "<i class='material-icons nav-toggle' toggle='true' toggled='false'>menu</i>";
        return $output."<ul $options>";
    }

    public function end(){
        return "</ul>";
    }

    public function items($items){
        $output = '';
        foreach($items as $item){
            $this->counter++;
            if(isset($item['url'])){
                $urlOptions = (isset($item['urlOptions']) ? $item['urlOptions'] : []);
                $urlOptions['href'] = $item['url'];

                $_item = "<a ".Html::constructAttributes($urlOptions).">";
            } else {
                $_item = '';
            }

            $_itemOptions = $this->itemOptions;

            $_class = isset($item['items']) ? ' nav-dropdown' : '';
            $_itemOptions['class'] =
                isset($_itemOptions['class']) ?
                    $_itemOptions['class'] . $_class
                    : $_class;

            $_itemOptions['toggled'] = "false";
            $_itemOptions['toggle'] = "true";
            $_itemOptions['data-item'] = "item-$this->counter";
            $itemOptions = Html::constructAttributes($_itemOptions);


            $_item .= "<li $itemOptions>";
            $_item .= "<span>" . (isset($item['label']) ? $item['label'] : '(not set)') . "</span>";

            $_item .= (isset($item['items']) ? "<ul>" . $this->items($item['items']) . "</ul>" : '');

            $_item .= "</li>";

            $_item .= (isset($item['url']) ? "</a>" : '');
            $output .= $_item;

        }
        $output .= "";

        return $output;
    }
}
?>
