<?php
namespace fragments\app;

use fragments\web\Request;
use fragments\web\Environment;
use fragments\web\Controller;

class Application{
    public function run(){
        include(__DIR__."/Frag.php");
        \Frag::$app = &$this;
        $this->root = dirname(dirname(__DIR__)).'/';
        $result = $this->init();
        if($result === true){
            $result =  $this->start();
        }        
        return $result;
    }

    protected function preInit(){
        define('DEV', 'development');
        define('PROD', 'production');
        if($_SERVER['SERVER_ADDR'] == '127.0.0.1'){
            define('STATE', DEV);
        } else {
            define('STATE', PROD);
        }
    }

    public function loadConfigurationFile($file, $stub = false){

        if(file_exists($file)){
            if($stub){
                $this->$stub = new \stdClass();
                foreach(include($file) as $k => $v){
                    $this->$stub->$k = $v;
                }

            }
            foreach(include($file) as $k => $v){
                $this->$k = $v;
            }
            return true;
        } else {
            return new \Exception("Missing file: $file", 404);
        }

    }

    public function init(){
        $this->preInit();


        $this->preload();

        $request = new Request();
        $this->request = &$request;

        session_start();
        $this->session = &$_SESSION;

        $environment = new Environment();
        $this->environment = &$environment;

        $r = $this->load();

        $this->controller = new Controller();

        return $r;
    }

    public function preload(){
        $commonConfigPath = $this->root.'/common/config/params.php';
        if($r = $this->loadConfigurationFile($commonConfigPath) !== true){
            return $r;
        }

        if(!isset($this->defaultEnvironment)){
            $this->defaultEnvironment = 'front';
        }
    }

    public function load(){
        // check if environment directory exists


        if(!is_dir($this->root.$this->environment->name)){
            return new \Exception("The environment does not exist: $message", 404);
        }



        $configPath = \Frag::$app->root.\Frag::$app->environment->dir."config/";

        if($r = $this->loadConfigurationFile($configPath."config.php") !== true){
            return $r;
        }

        if($r = $this->loadConfigurationFile($configPath."params.php", 'params') !== true){
            return $r;
        }

        if($r = $this->loadConfigurationFile($configPath."db.php", 'db') !== true){
            return $r;
        } else {
            $this->pdo = new \PDO( $this->db->pdo, $this->db->username, $this->db->password );
            if(STATE == DEV){
                $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            }
        }

        $urlClass = $this->urlClass;
        $className = isset($urlClass['class']) ? $urlClass['class'] : NULL;
        $c = new $className($urlClass[0]);
        $this->$className = $c;

        return true;
    }

    public function start(){
        return \Frag::handleRequest(\Frag::parseRequest());
    }
}

?>
