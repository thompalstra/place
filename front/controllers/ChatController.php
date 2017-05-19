<?php
namespace front\controllers;

use fragments\web\Controller;
use common\models\ChatClient;

class ChatController extends Controller{
    public function actionIndex($channel){
        $chatClient = new ChatClient();
        $chatClient->channel = $channel;
        return $this->render('index', [
            'chatClient' => $chatClient,
        ]);
    }
    public function actionConnect(){
        if($_POST){
            $chatClient = new ChatClient();
            $chatClient->channel = $_POST['channel'];
            return $chatClient->connect($_POST);
        }
    }

    public function actionMessage(){
        if($_POST){
            $chatClient = new ChatClient();
            $chatClient->channel = $_POST['channel'];
            return $this->response->json($chatClient->submitMessage($_POST));
        }
    }

    public function actionDisconnect(){
        $chatClient = new ChatClient();
        return $chatClient->disconnect();
    }

    public function actionHistory(){
        if($_POST){
            $chatClient = new ChatClient();
            $channel = $_POST['channel'];
            $offset = $_POST['offset'];
            $chatClient->channel = $_POST['channel'];
            $history = $chatClient->readHistory($offset);
            return $history;
        }
    }
}
