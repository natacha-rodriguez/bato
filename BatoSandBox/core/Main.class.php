<?php

require_once dirname(__FILE__) . '/Autoloader.class.php';

/**
 * Description of Init
 *
 * @author naty
 */
class Main {

    public static function init() {
        // framework’s front controller 
// specify parameters for autoloading classes 
        spl_autoload_register(NULL, FALSE);
        spl_autoload_extensions('.php');
        spl_autoload_register(array('Autoloader', 'load'));

        Context::init();

// define Autoloader class 
// handle request and dispatch it to the appropriate controller 
        try {
           $result = Dispatcher::dispatch();
           echo $result;
        } catch (Exception $e) {
            Logger::log( $e->getMessage());
            exit();
        }// End front controller
    }

}

class ClassNotFoundException extends Exception {
    
}

?>
