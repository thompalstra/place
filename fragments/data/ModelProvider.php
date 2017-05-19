<?php
namespace fragments\data;

class ModelProvider{
    public $query;
    public $pagination = [
        'pageSize' => 10,
        'page' => 1,
    ];

    public function __construct($params){
        $this->query = $params['query'];
        if(isset($params['pagination'])){
            $this->pagination = new Pagination($params['pagination']);
        } else {
            $this->pagination = false;
        }



    }

    public function getModels(){
        $query = $this->query;
        if($this->pagination){
            $clone = clone $this->query;
            $this->pagination->total = $clone->count();
            if(isset($_GET['sort'])){
                $attribute = $_GET['sort'];
                $isDown = strpos($attribute, '-');
                if($isDown !== false){
                    $attribute = substr($attribute, 1, strlen($attribute));
                    $ORDER = 'DESC';
                } else {
                    $ORDER = 'ASC';
                }

                $query->orderBy([$attribute => $ORDER]);
            }
            $query->limit($this->pagination->pageSize);
            $query->offset($this->pagination->pageSize * ($this->pagination->page - 1));

        }
        return $query->all();
    }
}

?>
