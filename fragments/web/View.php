<?php
namespace fragments\web;

class View{
    public $file;
    public $layout;

    public $headerAssets = [];
    public $footerAssets = [];

    public $data = [];

    public $registeredAssets = [];

    public function render($data = []){

        if (is_array($data)) {
        extract($data, EXTR_PREFIX_SAME, 'data');
        } else {
            $data = $this->data;
        }

        ob_start();
        include($this->file);
        $content = ob_get_contents();
        ob_end_clean();
        ob_start();
        include ($this->layout);
        ob_end_flush();
    }
    public function renderPartial($file, $data = []){

        $this->file = \Frag::$app->root.$file;
        if (is_array($data)) {
        extract($data, EXTR_PREFIX_SAME, 'data');
        } else {
            $data = $this->data;
        }
        ob_start();
        include($this->file);
        $content = ob_get_contents();
        ob_end_clean();
        ob_start();
        echo $content;
        ob_end_flush();
    }
    public function renderAjax(){

    }

    public function registerAsset($asset){
        $js = $asset->js;
        $css = $asset->css;
        $env = \Frag::$app->environment->name;
        foreach($js as $jsFile){
            $this->footerAssets[] = "<script src='$jsFile'></script>";
        }
        foreach($css as $cssFile){
            $this->headerAssets[] = "<link rel='stylesheet' type='text/css' href='$cssFile'>";
        }
    }

    public function registerJs($js){
        $this->registeredAssets[] = "<script>$js</script>";
    }

    public function head(){
        $output = "";
        foreach($this->headerAssets as $file){
            $output .= $file;
        }
        return $output;
    }
    public function footer(){
        $output = "";
        foreach($this->footerAssets as $file){
            $output .= $file;
        }
        foreach($this->registeredAssets as $file){
            $output .= $file;
        }
        return $output;
    }

    public static function get($view){
        $v = new View();

        if(strpos($view, '/')){
            $file = \Frag::$app->root.$view;
        } else {
            $file = \Frag::$app->root.\Frag::$app->environment->dir."views/".\Frag::$app->controller->filename."/$view.php";
        }

        $layout = \Frag::$app->root.\Frag::$app->environment->dir."layouts/".\Frag::$app->controller->layout;

        if(!file_exists($layout)){
            return new \Exception("Layout file does not exist: $layout", 404);
        }

        if(!file_exists($file)){
            return new \Exception("View file does not exist: $file", 404);
        }

        $v->file = $file;
        $v->layout = $layout;
        return $v;
    }
}
?>
