<?php
namespace fragments\data;

use fragments\helpers\Html;

class Pagination{

    public $total;

    public $pageSize;
    public $page;

    public function __construct($params){
        foreach($params as $k => $v){
            $this->$k = $v;
        }


        $this->page = (isset($_GET['page']) ? $_GET['page'] : $this->page);
        $this->pageSize = (isset($_GET['per-page']) ? $_GET['per-page'] : $this->pageSize);
    }

    public function createPager(){
        $pages = ceil($this->total / $this->pageSize);

        $start = ($this->page > 2) ? ($this->page - 2) : 1;

        $end = ($this->page <= ($pages -2)) ? $this->page + 2 : $pages;

        $output = "<ul class='pagination'>";
        $output .= ($start > 1) ? "<a href='?page=1&per-page=$this->pageSize'><li><i class='material-icons'>first_page</i></li></a>" : '';
        while($start <= $end){
            $class = ($start == $this->page) ? 'active' : '';
            $url = "?page=$start&per-page=$this->pageSize";
            $url .= isset($_GET['sort']) ? '&sort='.$_GET['sort'] : '';
            $output .= "<a class='$class' href='$url'><li>$start</li></a>";
            $start++;
        }
        $url = "?page=$pages&per-page=$this->pageSize";
        $url .= isset($_GET['sort']) ? '&sort='.$_GET['sort'] : '';

        $output .= ($end < $pages) ? "<a href='$url'><li><i class='material-icons'>last_page</i></li></a>" : '';
        $output .= "</ul>";

        return $output;
    }

    public function createScroller(){
        $pages = ceil($this->total / $this->pageSize);
        if($this->page < $pages){

            $p = $this->page + 1;

            $page = ($p <= $pages) ? $p : 1;
            $pageSize = $this->pageSize;

            return Html::a("<div data-pagination-infinite='true'>load more</div>", "?page=$page&pageSize=$pageSize", ['class'=>'pagination-load-more']);
        } else {
            return "<div class='pagination-no-more' data-pagination-end='true' style='animation: fade-out 6s forwards;'>no more</div>";
        }

    }
}

?>
