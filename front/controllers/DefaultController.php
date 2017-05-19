<?php
namespace front\controllers;

use fragments\web\Controller;
use common\models\ChatClient;

class DefaultController extends Controller{
    public function actionIndex(){
        $chatClient = new ChatClient();
        $chatClient->channel = 'default';
        return $this->render('index', [
            'chatClient'=>$chatClient
        ]);
    }

    public function actionError($exception){
        return $this->render('error', ['exception'=>$exception]);
    }
}

?>
