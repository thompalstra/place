<?php
namespace fragments\base;

use fragments\helpers\Html;
use fragments\helpers\StringHelper;

class Model extends \fragments\db\Record{

    const SCENARIO_DEFAULT = 'default';

    public $_scenario = 'default';
    public $_errors = [];
    public $_validated = false;

    public function __get($name){
        if (property_exists($this, $name)) {
          return $this->$name;
        }
        else {
            $f = 'get'.ucwords($name);
            if(method_exists($this, $f)){
                return $this->$f();
            }
        }
    }

    public function load($params = []){
        $class = $this->getClassName();

        // checks if class is contained within array
        if(isset($params[$class])){
            if(method_exists($this, 'rules')){
                // load via rules, where only attributes included in the rules are exclusively loaded
                $rules = $this::rules();
                foreach($rules as $rule){
                    $attributes = $rule[0];
                    foreach($attributes as $attribute){
                        if(isset($params[$class][$attribute])){
                            $this->$attribute = $params[$class][$attribute];
                        } else {
                            $this->$attribute = NULL;
                        }
                    }
                }
            } else{
                // load via default by setting all keys in the array as all values within the Model
                foreach($params[$class] as $key => $value){
                    $this->$key = $value;
                }
            }
            return true;
        }
        return false;

    }

    public static function getClass(){
        return get_called_class();
    }

    public function getClassName(){
        $class = get_called_class();
        return substr($class, strrpos($class, "\\")+1, strlen($class));
    }

    // public function getAttributeLabel($attribute){
    //     if(method_exists($this, 'attributeLabels')){
    //         $labels = $this->attributeLabels();
    //         if(isset($labels[$attribute])){
    //             return $this->attributeLabels()[$attribute];
    //         }
    //     }
    //     return StringHelper::prettify($attribute);
    // }

    public function setScenario($scenario){
        $this->_scenario = $scenario;
    }

    public function validate(){
        $r = false;
        if(method_exists($this, 'rules')){
            $rules = $this::rules();
            foreach($rules as $rule){
                $attributes = $rule[0];
                $validator = $rule[1];
                $scenario = isset($rule['on']) ? $rule['on'] : self::SCENARIO_DEFAULT;
                if($this->_scenario == $scenario){
                    foreach($attributes as $attribute){
                        $this->$validator($attribute, $rule);
                    }
                }
            }
        }
        if(!$this->hasErrors()){
            $r = true;
        }
        $this->_validated = true;
        return $r;
    }


    public function addError($attribute, $message){
        $this->_errors[$attribute][] = $message;
    }
    public function getErrors(){
        return $this->_errors;
    }
    public function getError($attribute){
        if(isset($this->_errors[$attribute])){
            return $this->_errors[$attribute][0];
        } else {
            return NULL;
        }
    }

    public function hasError($attribute){
        return isset($this->_errors[$attribute]);
    }

    public function hasErrors(){
        if(!empty($this->_errors)){
            return true;
        }
        return false;
    }

    public function email($attribute, $rule){
        if (filter_var($this->$attribute, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        $this->addError($attribute, $this->getAttributeLabel($attribute) . " must be an email address");
    }
    public function required($attribute, $rule){
        if(isset($this->$attribute) && !empty($this->$attribute)){
            return true;
        } else {
            $this->addError($attribute, $this->getAttributeLabel($attribute) . " cannot be empty");
        }
    }
    public function unique($attribute, $rule){
        $exists = self::findOne(['='=>[$attribute=>$this->$attribute]]);
        if($exists){
            $this->addError($attribute, $this->getAttributeLabel($attribute) . " already exists");
        }
    }
    public function number($attribute, $rule){

        if($this->$attribute == 'on'){
            $this->$attribute = 1;
            return;
        }
        if(is_numeric($this->$attribute)){
            return;
        }
        $this->addError($attribute, $this->getAttributeLabel($attribute) . " is not a number");
    }
    public function int($attribute, $rule){
        if($this->$attribute === 'on'){
            return $this->$attribute = 1;
        } else {
            if($this->$attribute === null){
                return $this->$attribute = 0;
            }
            $intval = intval($this->$attribute);
            if(is_int($intval)){
                return;
            }
        }
        $this->AddError($attribute, $this->getAttributeLabel($attribute) . " is not an integer");
    }
    public function string($attribute, $rule){
        if(isset($rule['min'])){
            $min = $rule['min'];
            if(strlen($this->$attribute) < $min){
                $this->addError($attribute, $this->getAttributeLabel($attribute) . " must be longer than $min characters");
            }
        }
        if(isset($rule['max'])){
            $max = $rule['max'];
            if(strlen($this->$attribute) > $max){
                $this->addError($attribute, $this->getAttributeLabel($attribute) . " must be longer than $max characters");
            }
        }
    }
    public function file($attribute, $rule){

    }

    public function safe($attribute, $rule){
        return true;
    }

    public function getAttributeLabel($attribute){
        if(method_exists($this, 'labels')){
            $labels = $this->labels();
            if(isset($labels[$attribute])){
                return $this->labels()[$attribute];
            }
        }
        return StringHelper::prettify($attribute);
    }


    public function beforeSave(){}
    public function afterSave(){}
    public function beforeFind(){}
    public function afterFind(){}
}
?>
