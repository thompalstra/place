<?php
namespace back\controllers;

use fragments\web\Controller;
use fragments\data\ModelProvider;
use common\models\Blog;
use common\models\BlogContent;

class BlogController extends Controller{
    public function actionIndex(){
        $query = Blog::find()->where([
            '=' => [
                'is_deleted' => 0,
            ]
        ]);

        $modelProvider = new ModelProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
                'page' => 1,
            ],
        ]);

        return $this->render('index', ['modelProvider'=>$modelProvider]);
    }

    public function actionView($id = null){

        // $c = BlogContent::findOne(['='=>['id'=>116]]);
        //
        // var_dump(json_encode($c->content)); die();

        $model = (!empty($id) ? Blog::findOne(['='=>['id'=>$id]]) : new Blog());
        $status = null;
        if($_POST && $model->load($_POST)){
            if($model->save()){
                $model->saveContent(isset($_POST['BlogContent']) ? $_POST['BlogContent'] : []);
                $status = true;
                if($model->newModel){
                    return $this->redirect("/blog/view?id=$model->id");
                }
            } else {
                $status = false;
            }

        }
        return $this->render('view',['model'=>$model,'status'=>$status]);
    }

    public function actionGetContent($identifier, $class){
        $classList = BlogContent::getClassList();
        $typeList = BlogContent::getTypeList();

        $class = (empty($class) ? array_values($classList)[0] : $class);
        $type = array_values($typeList)[0];

        $content = new BlogContent();
        $content->id = $identifier;
        $content->class = $class;
        $content->type = $type;
        return $this->renderPartial('/back/views/blog/_content.php', [
            'content'=> $content,
            'classList' => $classList,
            'typeList' => $typeList,
        ]);
    }

    public function actionRemove($id = null){
        if($id !== null){
            $model = Blog::findOne(['='=>['id'=>$id]]);
            $model->is_deleted = 1;
            $r = $model->save();
        }
        return $this->redirect(\Frag::$app->request->httpReferer);
    }

    public function actionError($exception){
        return $this->render('error', ['exception'=>$exception]);
    }

    public function actionRemoveAll($collection){
        $c = (array)json_decode($collection);
        foreach($c as $key => $value){
            $m = Blog::findOne(['='=>['id'=>$value]]);
            if($m){
                $m->is_deleted = 1;
                $m->save();
            }
        }
        return $this->response->json(['result'=>true]);
    }
    public function actionPublishAll($collection){
        $c = (array)json_decode($collection);
        foreach($c as $key => $value){
            $m = Blog::findOne(['='=>['id'=>$value]]);
            if($m){
                $m->is_published = 1;
                $m->save();
            }
        }
        return $this->response->json(['result'=>true]);
    }
    public function actionUnPublishAll($collection){
        $c = (array)json_decode($collection);
        foreach($c as $key => $value){
            $m = Blog::findOne(['='=>['id'=>$value]]);
            if($m){
                $m->is_published = 0;
                $r = $m->save();
            }
        }
        return $this->response->json(['result'=>true]);
    }
}

?>
