<?php
namespace fragments\db;

class Query{

    public function __construct(){
        $this->pdo = \Frag::$app->pdo;
    }

    public $queryArray = [];
    public $className;

    public function setClass($className){
        $this->className = $className;
    }

    public function describe($table){
        return $this->add('DESCRIBE', $table);
    }
    public function select($param){
        $this->queryArray['SELECT'] = [];
        return $this->add('SELECT', $param);
    }
    public function from($param){
        $this->queryArray['FROM'] = [];
        return $this->add('FROM', $param);
    }

    public function count(){
        $sth = $this->pdo->prepare($this->toString());
        $sth->execute();
        return $sth->rowCount();
    }
    public function limit($limit){
        $this->queryArray['LIMIT'][] = $limit;
        //return $this->add('LIMIT', $limit);
        return $this;
    }
    public function offset($offset){
        return $this->add('OFFSET', $offset);
    }
    public function orderBy($params){
        $c = [];
        foreach($params as $column => $sort){
            $c[] = "$column $sort";
        }
        $c = implode(', ', $c);
        $this->add('ORDER BY',$c);
        return $this;
    }

    public function leftJoin($a, $b){
        $b = $b[0];
        $this->add('LEFT JOIN',"$a ON ($b)");
    }

    public function innerJoin($params){
        $s = '';
        //$s = '(';
        $a = [];
        foreach($params as $column => $attribute){
            if(is_object($attribute)){
                if(get_class($attribute) == "core\base\QueryExpression"){
                    $a[] = "($attribute->query) $column";
                }
            } else {
                $a[] = $column . ' ' . $this->sanitizeAttribute($attribute);
            }
        }
        $s .= implode(' AND ', $a);
        $this->add('INNER JOIN', $s);

        $t = '';
        //$s .= ')';

        return $this;
    }

    public function on($params){
        $t = '';
        foreach($params as $key => $value){
            $t .= "$key = $value";
        }
        $t .= '';
        $this->add('ON', $t);
    }


    public function where($params){
        if(is_array($params)){
            //$s = '(';
            $a = [];
            foreach($params as $type => $search){
                if(is_string($search)){
                    $a[] = $search;
                } else if(is_array($search)){
                    foreach($search as $column => $attribute){
                        if(is_object($attribute)){
                            if(get_class($attribute) == "core\base\QueryExpression"){
                                $a[] = $column . ' ' . $type . "($attribute->query)";
                            }
                        } else {
                            $a[] = $column . ' ' . $type . ' ' . $this->sanitizeAttribute($attribute);
                        }
                    }
                }
            }
            $s = implode(' AND ', $a);
            //$s .= ')';
            $this->add('WHERE', "($s)");
        } else if(is_string($params)){
            $this->add('AND', $params);
        }
        return $this;
    }
    public function andWhere($params){
        if(is_array($params)){
            //$s = '(';
            foreach($params as $type => $search){
                if(is_string($search)){
                    $a[] = $search;
                } else if(is_array($search)){
                    foreach($search as $column => $attribute){
                        if(is_object($attribute)){
                            if(get_class($attribute) == "core\base\QueryExpression"){
                                $a[] = $column . ' ' . $type . "($attribute->query)";
                            }
                        } else {
                            $a[] = $column . ' ' . $type . ' ' . $this->sanitizeAttribute($attribute);
                        }
                    }
                }
            }
            $s = implode(' AND ', $a);
            //$s .= ')';
            $this->add('AND', "($s)");
        } else if(is_string($params)){
            $this->add('AND', $params);
        }
        return $this;
    }

    public function orWhere($params){
        if(is_array($params)){
            //$s = '(';
            foreach($params as $type => $search){
                if(is_string($search)){
                    $a[] = $search;
                } else if(is_array($search)){
                    foreach($search as $column => $attribute){
                        if(is_object($attribute)){
                            if(get_class($attribute) == "core\base\QueryExpression"){
                                $a[] = $column . ' ' . $type . "($attribute->query)";
                            }
                        } else {
                            $a[] = $column . ' ' . $type . ' ' . $this->sanitizeAttribute($attribute);
                        }
                    }
                }
            }
            $s = implode(' AND ', $a);
            //$s .= ')';
            $this->add('OR', "($s)");
        } else if(is_string($params)){
            $this->add('AND', $params);
        }
        return $this;
    }

    public function groupBy($params){
        $this->add('GROUP BY', $params);
    }

    public function sanitizeAttribute($value){
        if(is_numeric($value)){
           return $value;
        } else if(is_string($value)){
            if(empty($value)){
                return 'NULL';
            } else {
                return $this->pdo->quote($value) ;
            }

        } else if($value == NULL){
            return 'NULL';
        }
    }

    public function add($key, $insert){
        $this->queryArray[$key][] = $insert;
    }

    public function toString(){
        $q = '';
        foreach($this->queryArray as $key => $value){
            foreach($value as $_value){
                $q .= $key . ' ' . $_value . ' ';
            }
        }
        return $q;
    }
    public function one(){
        $this->queryString = $this->toString();
        $sth = $this->pdo->prepare($this->queryString);
        $sth->execute();
        $sth->setFetchMode(\PDO::FETCH_CLASS, $this->className,[false]);
        return $sth->fetch();
    }

    public function update($params){
        $this->add("UPDATE", $params);
    }
    public function deleteFrom($tableName){
        $this->add("DELETE FROM", $tableName);
    }
    public function set($params){
        $this->add("SET", $params);
    }

    public function fetchOne(){

    }
    public function all(){
        $this->queryString = $this->toString();
        $sth = $this->pdo->prepare($this->queryString);
        $sth->execute();
        $sth->setFetchMode(\PDO::FETCH_CLASS, $this->className,[false]);
        $r = $sth->fetchAll();
        return $r;
    }

    public function execute($command){
        $sth = $this->pdo->prepare($command);
        return $sth->execute();
    }
}
?>
