<?php
namespace common\models;

use fragments\base\Model;

class File extends Model{

    public $new_content;

    //public static $sourcePath = '/web/uploads';
    public static $sourcePath = '/';

    public static function deleteFile($path){
        return unlink($path);
    }

    public static function deleteDir($path){
        return @rmdir($path);
    }

    public function updateContent(){
        return @file_put_contents($this->path, $this->new_content);;
    }

    public function getDirectory(){
        return dirname($this->relativePath);
    }

    public static function rename($path, $name){
        $path = \Frag::$app->root . $path;
        $newName = substr($path, 0, strrpos($path, '/')+1) . $name;
        //var_dump($newName); die();
        if(file_exists($path)){
            if(is_dir($path)){
                return rename($path, $newName);
            } else {
                return rename($path, $newName);
            }
        } else {
        }
    }
    public static function create($path, $name, $type){

        $fullPath = \Frag::$app->root . $path . '/' . $name;
        if($type == 'dir'){

            if(file_exists(\Frag::$app->root . $path)){
                return @mkdir($fullPath, 0777);
            }
        } else if($type == 'file') {
            fopen($fullPath, "w");
            return true;
        }
        return false;
    }

    public static function generateBreadCrumbItems($path){
        $path = trim($path,'/');
        $explode = explode('/', $path);
        $prefixUrl = '/files/index?path=';
        $url = '';

        $items = [];

        $items[] = [ 'label' => '<i class="icon material-icons">home</i>', 'url' => '/files/index' ];

        if($path == ''){
            return $items;
        }
        foreach($explode as $key => $part){
            $url .= $part.'/';
            $items[] = [ 'label' => $part, 'url' => $prefixUrl.$url ];
        }
        return $items;
    }

    public static function getDirectoryContents($path){
        $r = [];
        $root = '';
        // if(strlen($path) < strlen(self::$sourcePath)){
        //     $path = self::$sourcePath;
        // }
        foreach(scandir(\Frag::$app->root.$path) as $item){
            $p = $path;
            $type = is_dir("$root.$path/$item") ? 'dir' : 'file';

            if($item == '.'){
                $p = substr($p, 0, strrpos($p, '/'));
                $name = 'up';
                $type = 'return';
            } else if($item == '..'){
                continue;
            } else {
                $p = "$p/$item";
                $name = $item;
            }
            $p = str_replace('//', '/', $p);
            $r[] = [
                'type' => $type,
                'name' => $name,
                'path' => $p,
            ];
        }
        return $r;
    }

    public static function getInfo($file){
        $fileInfo = new File();
        $fileInfo->relativePath = $file;
        $fileInfo->path = \Frag::$app->root . $file;
        $fileInfo->mimeType = mime_content_type($fileInfo->path);
        $fileInfo->type = substr($fileInfo->mimeType, 0, strrpos($fileInfo->mimeType, '/'));

        return $fileInfo;
    }
}

?>
