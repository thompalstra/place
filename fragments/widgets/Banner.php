<?php
namespace fragments\widgets;

use fragments\widgets\Widget;
use fragments\helpers\Html;
use fragments\helpers\StringHelper;

class Banner extends Widget{

    public $items = [];

    public $slideSpeed = 2000;
    public $holdSpeed = 2000;
    public $animation = 'ease-in-out';
    public $progress = true;

    const ANIMATION_LINEAR = 'linear';
    const ANIMATION_EASE_IN_OUT = 'ease-in-out';

    public $options = [
        'class' => 'slide-container container-default',
        'id' => 'slide-container',
    ];

    public $itemOptions = [
        'class' => 'slide slide-default',
    ];

    public $output;


    public function prepare(){

        $this->options['data-slide'] = $this->slideSpeed;
        if($this->progress){
            $this->options['data-timeout'] = 'bt'.StringHelper::toCamelCase($this->options['id']);
        }

        $this->output = $this->begin();
        $this->output .= "<i class='material-icons previous-slide'>arrow_back</i>";
        $this->output .= $this->items($this->items);
        $this->output .= "<i class='material-icons next-slide'>arrow_forward</i>";
        $this->output .= $this->end();

        $this->js();
    }

    public function run(){
        return $this->output;
    }

    public function begin(){
        $output = "<section ".Html::constructAttributes($this->options).">";
        if($this->progress){
            $this->options['data-timeout'] = 'bt'.StringHelper::toCamelCase($this->options['id']);
            $_time = ($this->holdSpeed / 1000)."s";

            $progressOptions = [];
            $progressOptions['class'] = 'progress';
            $progressOptions['style'] = [
                'transition' => "all $_time $this->animation"
            ];
            $progressOptions['data-transition'] = "all $_time $this->animation";
            $output .= "<div ".Html::constructAttributes($progressOptions)."></div>";
        }
        return $output;
    }
    public function end(){
        return "</section>";
    }

    public function items($items){
        $s = $this->slideSpeed / 1000;
        $time = ($this->slideSpeed / 1000)."s";
        $function = $this->animation;

        $innerContainerOptions = [
            'style' => [
                'transition' => "all $time $function",
                'left' => '0',
            ],
            'class' => 'inner-container',

        ];
        $innerContainerOptions = Html::constructAttributes($innerContainerOptions);

        $output = "<section $innerContainerOptions>";
        $ctr = 0;
        foreach($items as $slide){
            $itemOptions = $this->itemOptions;
            $itemOptions['style'] = isset($itemOptions['style']) ? $itemOptions['style'] : [];

            $img = $slide['img'];

            if($ctr == 0){
                $itemOptions['class'] = isset($itemOptions['class']) ? $itemOptions['class'] . ' active-slide' : '';
            }
            $itemOptions['style']['background-size'] = 'cover';
            $itemOptions['style']['background-image'] = "url($img)";
            $itemOptions['style']['left'] = "calc(100% * $ctr)";

            $itemOptions = Html::constructAttributes($itemOptions);



            $item = '<a href="'.$slide['url'].'"/>';

            $item .= "<div $itemOptions></div>";

            $item .= '</a>';

            $output .= $item;
            $ctr++;
        }
        $output .= "</section>";
        return $output;
    }

    public function js(){
        if(isset($this->options['id'])){
            $id = $this->options['id'];
            $friendlyId = StringHelper::toCamelCase($id);
            $hold = $this->holdSpeed + $this->slideSpeed;
$js = <<<JS
    // progress = _("#$id")[0][0].querySelector('.progress');
    // if(progress){
    //     setTimeout(function(e){
    //         progress.style.width = '100%';
    //         progress.style.opacity = '1';
    //     }, "$this->slideSpeed");
    //
    //     var bt$friendlyId;
    // }
    //
    // var functionBanner$friendlyId = function(e){
    //     next = _("#$id").findOne('.next-slide');
    //     next.trigger('click');
    // }
    //
    // var interval$friendlyId = setInterval(functionBanner$friendlyId, "$hold");
    //
    // _(document).on('click', "#$id", function(e){
    //     clearInterval(interval$friendlyId);
    //     interval$friendlyId = setInterval(functionBanner$friendlyId, "$hold");
    // });
JS;
        }
        \Frag::$app->view->registerJs($js);
    }
}

?>
