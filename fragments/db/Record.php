<?php
namespace fragments\db;
use fragments\base\Model;
use fragments\db\Query;
class Record extends Query{

    public $_attributes = [];
    public $newModel = true;

    public function __construct($new = true){
        $this->setScenario(Model::SCENARIO_DEFAULT);
        $this->newModel = $new;
        if(!$this->newModel){
            foreach($this as $key => $value){
                if(!property_exists(get_called_class(), $key)){
                    $this->_attributes[$key] = &$this->$key;
                }
            }
            $this->afterFind();
        } else if(method_exists($this, 'tableName')) {
            foreach($this->getColumns() as $key){
                $this->$key = NULL;
                $this->_attributes[$key] = &$this->$key;
            }
        }
    }

    public static function getColumns(){
        $query = new Query();
        $class = get_called_class();
        $sth = $query->pdo->prepare("DESCRIBE " . $class::tableName());
        $sth->execute();
        return $sth->fetchAll(\PDO::FETCH_COLUMN);
    }

    public static function find(){
        $model = self::getModel();
        $query = new Query();
        $query->setClass(get_called_class());
        $query->select($model::tableName().'.*');
        $query->from($model::tableName());
        return $query;
    }

    public static function findOne($params = []){
        $query = self::find();
        $query->where($params);
        return $query->one();
    }

    public static function findAll($params = []){
        $query = self::find();
        $query->where($params);
        return $query->all();
    }

    public static function updateAll($where = [], $set = []){
        $model = self::getModel();
        $query = new Query();
        $query->setClass(get_called_class());

        $query->update($model::tablename());

        $query->set($set);
        $query->where($where);
        return $query->execute($query->toString());
    }

    public static function getModel(){
        $className = get_called_class();
        return new $className();
    }

    public function save($validation = true){
        $this->beforeSave();
        $r = true;
        $o = false;
        if($validation){
            $o = $this->validate();

        }
        if(!$this->hasErrors()){
            if($this->newModel){
                $o = $this->recordInsert();
            } else {
                $o = $this->recordUpdate();
            }
            $this->afterSave();
        }

        return $o;
    }

    public function recordInsert(){
        $class = get_called_class();
        $table = $class::tableName();

        $query = new Query();

        foreach($this->_attributes as $key => $value){
            $values[$key] = $query->sanitizeAttribute($value);
            $keys[$key] = $key;
        }
        $keys = implode(', ', $keys);
        $values = implode(', ', $values);

        $query->add('INSERT INTO', "$table ($keys)");
        $query->add('VALUES', "($values)");
        $result = $query->execute($query->toString());
        if($result){
            $id = $query->pdo->lastInsertId();
            $query = self::find()->where([
                '=' => [
                    'id' => $id,
                ],
            ]);
            $model = $query->one();
            foreach($model as $key => $value){
                $this->$key = $value;
            }
            $result = $model;
        }
        return $result;
    }
    public function recordUpdate(){
        $class = get_called_class();
        $table = $class::tableName();

        $query = new Query();

        $values = [];
        foreach($this->_attributes as $key => $value){
            $v = $query->sanitizeAttribute($value);
            $values[] = "$key = $v";
        }
        $values = implode(',', $values);

        $query->update("$table");
        $query->set("$values");
        $query->where([
            '=' => ['id'=>$this->id],
        ]);
        $result = $query->execute($query->toString());
        return $result;
    }

    public function delete(){
        if(!empty($this->_attributes)){
            $class = get_called_class();
            $table = $class::tableName();
            $query = new Query();
            foreach($this->_attributes as $key => $value){
                $v = $query->sanitizeAttribute($value);
                $values[] = "$key = $v";
            }
            $values = implode(' AND ', $values);
            $command = "DELETE FROM $table WHERE $values";
            return $query->execute($command);
        }
    }

    public static function deleteAll($params){
        $query = new Query();
        $class = get_called_class();
        $query->deleteFrom($class::tableName());
        $query->where($params);
        return $query->execute($query->toString());
    }


}
?>
