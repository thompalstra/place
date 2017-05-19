<?php
namespace fragments\base;

class QueryExpression extends Query{

    public $query;

    public function __construct($arg){
        if(get_class($arg) == "core\base\Query"){
            $this->query = $arg->toString();
        }
    }
}
?>
