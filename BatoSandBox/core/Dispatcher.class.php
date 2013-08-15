<?php

class Dispatcher {

    // dispatch request to the appropriate controller/method 
    public static function dispatch() {

        $url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

        //array_shift($url);
        //Logger::log(__METHOD__ . ".... this is the url we got..." . var_export($url, true));
        $type = self::getRequestType($url);
        if ($type == 'Controller') {
            $url = array_slice($url, 2); // drop the /siteName/www/ part
            $return = self::dispatchController($url);
        } else {
            $return = self::dispatchFile($type, $url);
        }
        //$dispatchMethod = 'dispatch' . ucfirst($type);
        return $return;
    }

    protected static function dispatchController($url) {
        //Logger::log(__METHOD__.'server request is '. var_export($_SERVER, true));
        // get controller name 
        $areaName = !empty($url[0]) ? strtolower($url[0]):null;
        $controller = (!is_null($areaName))? ucfirst($areaName). 'Controller' : 'DefaultController';
//        Logger::log(__METHOD__.'... controller......'. var_export($controller, true));
        
        // get method name of controller 
        $method = !empty($url[1]) ? $url[1] : 'index';
  //      Logger::log(__METHOD__.'... method......'. var_export($method, true));
        // get arguments passed in to the method 
        $args = self::getArgs($url);
        $acl = new Acl();
        session_start();
        Logger::log(__METHOD__.'... session......'. var_export($_SESSION, true));
        $userId = (isset($_SESSION['userId']))?$_SESSION['userId']:null;
        // create controller instance and call the specified method 
        if (!class_exists($controller)) {
            Logger::log(__METHOD__.'... la clase no existe.........');
            $controller = 'DefaultController';
        }
        if(!$acl->authorize("$areaName/$method", $userId)){
            Logger::log(__METHOD__.'... no tiene permiso.........');
            $controller = 'DefaultController';
            $method = 'unauthorized';
        }
        $cont = new $controller();
    //    Logger::log(__METHOD__.'... llamando llamando.........');
        $return = $cont->$method($args);
        //Logger::log("dispatching a controller action, $controller -> $method with args..". var_export($args, true));
        return $return;
    }

    protected static function dispatchFile($type, $url) {
        //Logger::log(__METHOD__.'server request is '. var_export($_SERVER['REQUEST_URI'], true));
        $route = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $url[0] . '/local/resources/' . strtolower($type) . '/' . $url[count($url) - 1];
        //Logger::log('Redirecting to.....'. $route);
        header("Location: " . $route);
        //return $route.$url[count($url)-1];
    }

    protected static function getRequestType($url) {
        $last = $url[count($url) - 1];
        $temp = explode('.', $last);
        $type = 'Controller';
        if ($temp[0] != $last) { // extension found
            $extension = $temp[count($temp) - 1];
            if (in_array($extension, array('jpg', 'gif', 'png', 'jpeg', 'tiff', 'bmp'))) {
                $type = 'Img';
            } else {
                $type = strtolower($extension);
            }
        }
        return $type;
    }

    protected static function getArgs($url) {
        $temp = count($url) > 2 ? array_slice($url, 2) : array();
        $final = array();
        foreach ($temp as $param) {
            $val = explode('=', $param);
            $final[$val[0]] = (count($val) > 1) ? $val[1] : $val[0];
        }

        $postVars = $_POST;
        $final = array_merge($final, $postVars);
        return $final;
    }

}
