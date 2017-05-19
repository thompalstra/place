<?php
namespace fragments\widgets;

use fragments\widgets\Widget;
use fragments\helpers\Html;
use fragments\helpers\StringHelper;

class Table extends Widget{

    public $modelProvider;
    public $output;
    public $columns = [];
    public $options = [
        'class' => 'table table-default',
    ];

    public function prepare(){
        $output = $this->begin();
        $output .= $this->headers();
        $output .= $this->rows();
        $output .= $this->end();

        $this->output = $output;
    }
    public function run(){
        return $this->output;
    }

    public function begin(){
        $options = Html::constructAttributes($this->options);
        return "<table $options>";
    }

    public function createSortUrl($attribute){
        // get the original parameters
        $params = [
            'page' => $this->modelProvider->pagination->page,
            'pageSize' => $this->modelProvider->pagination->pageSize,
        ];
        $options = [];

        // checks if sorting is active
        if(isset($_GET['sort'])){
            // checks if the current sorting is desc and if the sorted attribute is the current attribute
            if(strpos($_GET['sort'], '-') !== false && substr($_GET['sort'], 1, strlen($_GET['sort'])) == $attribute){
                $params['sort'] = "$attribute";

            // checks if the current sorting = asc and if the sorted attribute is the current attribute
            } else if($_GET['sort'] == $attribute){
                $params['sort'] = "-$attribute";
                $options['class'] = 'sorted';
            } else {
                $params['sort'] = $attribute;
            }
        // if no sorting is applied, set the current sorting to the attribute, asc
        } else {
            $params['sort'] = $attribute;
        }
        $options['href'] = '?'.http_build_query($params);

        return '<a ' . Html::constructAttributes($options) . '>';
    }

    public function endSortUrl(){
        return "</a>";
    }

    public function headers(){
        $output = "";
        foreach($this->columns as $key => $modelAttribute){
            $title = "";
            $options = [];
            if(is_array($modelAttribute)){
                if(isset($modelAttribute['header'])){
                    $title = $modelAttribute['header'];
                } else {
                    $title = StringHelper::prettify($key);
                }
                $options = (isset($modelAttribute['options']) ? $modelAttribute['options'] : []);
            } else {
                $title = StringHelper::prettify($modelAttribute);
            }
            $line = "<th ".Html::constructAttributes($options).">";

            //$line .= is_string($modelAttribute) ? '<a href="'.$this->createSorturl($modelAttribute).'">' : '';
            $line .= is_string($modelAttribute) ? $this->createSorturl($modelAttribute) : '';
            $line .= $title;
            $line .= is_string($modelAttribute) ? $this->endSortUrl() : '';
            $line .= "</th>";
            $output .= $line;
        }
        return $output;
    }

    public function rows(){
        $output = "";
        foreach($this->modelProvider->getModels() as $model){
            $line = "<tr>";
            foreach($this->columns as $column){

                $options = new \stdClass();

                if(is_string($column)){
                    $options->value = $model->$column;
                } else if(is_array($column)){
                    $f = $column['value'];
                    $options->value = $f($model);
                }

                $line .= "<td>";
                $line .= $options->value;
                $line .= "</td>";
            }
            $line .= "</tr>";

            $output .= $line;
        }
        return $output;
    }

    public function end(){
        $output = "</table>";
        $output .= ($this->modelProvider !== false ? $this->modelProvider->pagination->createPager() : '');

        return $output;
    }

}
?>
