<?php

use fragments\web\Action;
use fragments\web\Controller;

class Frag{
    public static $app;
    public static $request;
    public static $session;
    public static $url;
    public static $method;
    public static $params = [];

    public static function handleRequest($route){

        $pathInfo = pathinfo($route[0]);
        $params = $route[1];

        // \Frag::$app->controller = Controller::get($pathInfo['dirname']);
        // \Frag::$app->action = Action::get($pathInfo['filename']);

        $c = Controller::get($pathInfo['dirname']);
        $a = Action::get($pathInfo['filename']);

        if(get_class($c) !== 'Exception'){
            if(get_class($a) !== 'Exception'){
                \Frag::$app->controller = $c;
                \Frag::$app->action = $a;
                return \Frag::$app->controller->runAction(\Frag::$app->action->action, $params);
            } else {
                return \Frag::$app->controller->runError($a);
            }
        } else {
            return \Frag::$app->controller->runError($c);
        }
    }

    public function runError($exception){
        var_dump($exception); die();
    }

    public static function parseRequest(){
        $params = $_GET;
        $route = \Frag::$app->url;
        if(strpos($route, '?')){
            $route = substr($route, 0, strpos($route, '?'));
        }

        $route = (strpos(\Frag::$app->url, '?') !== false ? substr(\Frag::$app->url, 0, strpos(\Frag::$app->url, '?')) : \Frag::$app->url );

        foreach(\Frag::$app->urlClass[0] as $key => $redirect){

            $currentRouteExplode = explode('/', ltrim($route, '/'));
            $currentKeyExplode = explode('/', ltrim($key, '/'));

            if(count($currentRouteExplode) == count($currentKeyExplode)){

                $count = count($currentRouteExplode);
                $offset = 0;
                $newParams = [];
                $matchCount = 0;
                while($offset < $count){
                    $_key = $currentKeyExplode[$offset];
                    $part = $currentRouteExplode[$offset];

                    if($_key == $part){
                        $matchCount++;
                    } else {
                        preg_match("/(:[^>]*>)/", $_key, $search);
                        if(isset($search[1])){
                            $toMatch = substr($search[1], 1, strlen($search[1])-2);
                            $toMatch =

                            preg_match("$toMatch", $_key, $out);
                            if($out[0]){
                                preg_match("/<([^>]*):/", $_key, $paramName);
                                $newParams[$paramName[1]] = $part;
                                $_GET[$paramName[1]] = $part;
                                $matchCount++;
                            }
                        }
                        preg_match("/:([^>]*)>/", $_key, $output_array);
                    }
                    $offset++;
                }

                if($matchCount == $count){
                    $params = $params + $newParams;
                    return [$redirect, $params];
                }
            }
        }

        if(empty($route) || $route == '/'){
            $route = \Frag::$app->defaultRoute;
        }

        //check if the controller is filled in, otherwise revert to defaultRoute controller
        $pathInfo = pathinfo($route);
        if($pathInfo['dirname'] == '\\'){
            $defaultRoute = \Frag::$app->defaultRoute;
            $route = substr($defaultRoute, 0, strrpos($defaultRoute,'/')).$route;
        }
        return [$route, $params];
    }
}

?>
