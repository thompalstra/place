<?php
namespace front\widgets;

use fragments\widgets\Widget;
use common\models\BlogCategory;

class Categories extends Widget{
    public $category_ids = [];
    public function prepare(){
        $categories = BlogCategory::find()->where([
            '=' => [
                'is_deleted' => 0,
            ],
        ])->all();
        $out = "<section class='category-container'>";
            $out .= "<h4>Filter categories</h4>";
            $out .= "<section class='inner'>";
                foreach($categories as $category){
                    //$line = "<a href='/blog/$category->slug' class='tag action'>";

                    $checked = (isset($this->category_ids[$category->id]) ? "checked" : '');

                    $line = "<input id='category-filter-$category->id' class='sitesearch-category-filter' type='checkbox' value='$category->id' name='SiteSearch[category_ids][]' $checked>";
                    $line .= "<label for='category-filter-$category->id'>$category->title</label>";
                    //$line .= "</a>";
                    $out .= $line;
                }
            $out .= "</section>";
        $out .= "</section>";
        $this->html = $out;
    }
    public function run(){
        return $this->html;
    }
}
