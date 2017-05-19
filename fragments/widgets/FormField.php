<?php
namespace fragments\widgets;

use fragments\helpers\Html;
use fragments\helpers\StringHelper;

class FormField{

    public $model;
    public $attribute;
    public $isArray = false;
    public $formOptions = [];
    public $label = true;

    public $output;

    public function textInput($params = []){
        $options = $this->before();
        $options['type'] = 'text';

        $options = $options + $params;

        return $this->generateInput($options);
    }

    public function select($items, $options = []){
        $attr = $this->attribute;
        $value = $this->model->$attr;
        return $this->generateSelect($value, $items, $this->before());
    }

    public function checkbox(){
        $options = $this->before();
        $options['type'] = 'checkbox';
        $attr = $this->attribute;
        if($this->model->$attr == true){
            $options['checked'] = '';
        }
        unset($options['value']);
        return $this->generateInput($options);
    }

    public function textarea($options = []){
        $attr = $this->attribute;
        $value = $this->model->$attr;
        return $this->generateTextarea($value, $this->before());
    }

    public function before(){
        $options = [];
        $this->isArray = (strpos($this->attribute,'[]') !== false) ? true : false;
        $this->attribute = ($this->isArray ? substr($this->attribute, 0, strpos($this->attribute,'[]')) : $this->attribute);

        $options['name'] = $this->model->classname . "[" . $this->attribute  . "]" . ($this->isArray ? "[]" : "");
        $options['id'] = StringHelper::formEncode($options['name']);
        $attr = $this->attribute;
        $options['value'] = $this->model->$attr;
        return $options;
    }

    public function rowBegin(){
        if($this->useLayout){
            $rowOptions = Html::constructAttributes($this->rowOptions);
            $output = "<div $rowOptions>";
                $output .= $this->generateLabel();
                $this->inputOptions['class'] = ($this->model->_validated ? $this->model->hasError($this->attribute) ? $this->inputOptions['class'] . ' error invalid' : $this->inputOptions['class'] . ' error valid' : $this->inputOptions['class']);
                $output .= "<div " . Html::constructAttributes($this->inputOptions) . ">";
            return $output;
        }
    }

    public function rowEnd(){
        if($this->useLayout){
                $output = ($this->model->hasError($this->attribute)) ? "<div class='error invalid'>".$this->model->getError($this->attribute)."</div>" : "";
                $output .= "</div>";
            $output .= "</div>";
            return $output;
        }
    }


    public function generateLabel(){
        $labelOptions = Html::constructAttributes($this->labelOptions);
        return ($this->label) ? "<div $labelOptions>".$this->model->getAttributeLabel($this->attribute)."</div>" : "";
    }

    public function generateSelect($value, $items, $options){
        $output = $this->rowBegin();
        //$output .= "<div " . Html::constructAttributes($this->inputOptions) . ">";
        $output .= Html::selectInput([
            'options' => $options,
            'value' => $value,
            'data' => $items,
        ]);
        //$output .= "</div>";
        $output .= $this->rowEnd();
        return $output;
    }

    public function generateTextarea($value, $options){
        $output = $this->rowBegin();
        //$output .= "<div " . Html::constructAttributes($this->inputOptions) . ">";
        $output .= Html::textarea([
            'options' => $options,
            'value' => $value,
        ]);
        //$output .= "</div>";
        $output .= $this->rowEnd();
        return $output;
    }

    public function generateInput($options){
        $output = $this->rowBegin();

        $inputOptions = Html::constructAttributes($this->inputOptions);
        //$output .= "<div $inputOptions>";
        $output .= "<input ".Html::constructAttributes($options).">";
        //$output .= "</div>";
        $output .= $this->rowEnd();
        return $output;
    }

    public function widget($widgetClass, $params){
        $output = $this->rowBegin();
        $inputOptions = Html::constructAttributes($this->inputOptions);

        $widget = new $widgetClass();
        //$output .= "<div $inputOptions>";

        $params['inputOptions'] = $this->before();
        $attr = $this->attribute;
        $params['value'] = $this->model->$attr;

        $output .= $widget::widget($params);

        //$output .= "</div>";
        $output .= $this->rowEnd();
        return $output;

    }
}
?>
