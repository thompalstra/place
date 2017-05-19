<?php
namespace fragments\widgets;

use fragments\helpers\Html;

class Form{

    public $options = [
        'class' => 'form form-default',
        'method' => 'POST',
    ];

    public $rowOptions = [
        'class' => 'form form-row',
    ];

    public $labelOptions = [
        'class' => 'label label-row col dt12 tb12 mb12',
    ];

    public $inputOptions = [
        'class' => 'input input-row col dt12 tb12 mb12',
    ];

    public $useLayout = true;

    public static function begin($params){

        $form = new Form();
        $form->rowOptions = (isset($params['rowOptions']) ? $form->rowOptions + $params['rowOptions'] : $form->rowOptions);
        $form->labelOptions = (isset($params['labelOptions']) ? $form->labelOptions + $params['labelOptions'] : $form->labelOptions);
        $form->inputOptions = (isset($params['inputOptions']) ? $form->inputOptions + $params['inputOptions'] : $form->inputOptions);

        $form->useLayout = isset($params['useLayout']) ? $params['useLayout'] : true;

        $options = $form->options;

        foreach($params as $key => $value){
            $options[$key] = $value;
        }

        $options = Html::constructAttributes($options);
        echo "<form $options>";
        return $form;
    }

    public static function end(){
        return "</form>";
    }

    public function field($model, $attribute){
        $field = new FormField();
        $field->model = $model;
        $field->attribute = $attribute;
        $field->rowOptions = $this->rowOptions;
        $field->inputOptions = $this->inputOptions;
        $field->labelOptions = $this->labelOptions;
        $field->useLayout = $this->useLayout;
        return $field;
    }

    public static function status($status, $options = ['class'=>'form form-status']){
        if($status !== null){
            $s = ($status == true) ? 'true success' : 'false alert';
            $options['class'] = isset($options['class']) ? $options['class'] . " $s" : $options['class'] = "";
            $options = Html::constructAttributes($options);

            $state = ($status == true) ? "Success" : "Error";
            return "<div $options><label>$state</label></div>";

        }
        return "";

    }


}
?>
