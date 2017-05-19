<?php
namespace front\widgets;

use fragments\widgets\Widget;
use common\models\Blog;

class RelatedBlogItems extends Widget{

    public $categoryId;
    public $blogId;

    public $html;

    public function prepare(){
        $query = Blog::find()->where([
            '=' => [
                'category_id' => $this->categoryId,
                'is_deleted' => 0,
                'is_published' => 1,
            ],
            '!=' => [
                'id' => $this->blogId,
            ],
        ]);
        $output = "<section class='sidebar category-widget'>";
        foreach($query->all() as $model){
            $line = "<a href='$model->url' class='link'>";
            $line .= "<section class='blog-item-small col dt12 tb12 mb12'>";
            if($model->summary){
                $line .= "<section class='summary'>".$model->summary->content."</section>";
            }
            $line .= "<h4 class='title'>$model->title</h4>";
            $line .= "</section>";
            $line .= "</a>";
            $output .= $line;
        }
        $output .= "</section>";

        $this->html = $output;
    }
    public function run(){
        return $this->html;
    }
}

?>
