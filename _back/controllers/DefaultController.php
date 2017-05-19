<?php
namespace back\controllers;

use fragments\web\Controller;

class DefaultController extends Controller{
    public function actionIndex(){
        return $this->render('index', ['a'=>'b']);
    }

    public function actionError($exception){
        return $this->render('error', ['exception'=>$exception]);
    }
}

?>
