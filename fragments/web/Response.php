<?php
namespace fragments\web;

class Response{
    public function json($data){
        return json_encode($data);
    }
}

?>
