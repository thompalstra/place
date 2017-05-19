<?php
function __autoload($classname){
    $classname = str_replace('\\', '/', $classname);
    if(file_exists(dirname(__DIR__).'/'.$classname.'.php')){
        include (dirname(__DIR__).'/'.$classname.'.php');
    }
}
?>
