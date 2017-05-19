<?php
namespace back\controllers;

use fragments\web\Controller;
use common\models\File;

class FilesController extends Controller{
    public function actionIndex(){
        $path = isset($_GET['path']) ? $_GET['path'] : File::$sourcePath;

        if(strlen($path) < strlen(File::$sourcePath)){
            $path = File::$sourcePath;
        }


        $contents = File::getDirectoryContents($path);

        return $this->render('index', [
            'contents' => $contents,
            'path' => $path,
        ]);
    }

    public function actionRename(){
        if($_POST){
            $newName = json_decode($_POST['name']);
            $path = json_decode($_POST['path']);
            $result = File::rename($path, $newName);

            return $this->response->json(['result'=>$result]);
        }
    }
    public function actionRemove(){
        if($_POST){
            $message = "";
            $hasError = false;
            $data = json_decode($_POST['data']);
            foreach($data as $item){
                $path = \Frag::$app->root . $item;
                if(file_exists($path)){
                    if(is_dir($path)){
                        $r = File::deleteDir($path);
                        // $r = @rmdir($path);
                        if(!$r){
                            $hasError = true;
                            $message .= "Could not delete directory: \n $path \n";
                        }
                    } else {
                        $r = File::deleteFile($path);
                        // $r = @unlink($path);
                        if(!$r){
                            $hasError = true;
                            $message .= "Could not delete file: \n $path \n";
                        }
                    }
                }
            }
            if(!$hasError){
                $message = 'Success!';
            }
            return $this->response->json(['result'=>true, 'message' => $message]);
        }
    }

    public function actionView($file){

        $file = File::getInfo($file);

        if($_POST && $file->load($_POST)){
            if($file->type == 'text' || $file->type == 'inode'){

                $r = $file->updateContent();
            }
        }


        if($file->type == 'text' || $file->type == 'inode'){
            $type = 'Text';
        } else {
            $type = ucwords($file->type);
        }


        return $this->render("view$type", [
            'fileInfo' => $file,
        ]);
    }

    public function actionCreate(){
        if($_POST){
            $result = false;
            $path = json_decode($_POST['path']);
            $name = json_decode($_POST['name']);
            $type = json_decode($_POST['type']);
            $result = File::create($path, $name, $type);
            return $this->response->json(['result'=>$result]);
        }
    }
}
?>
