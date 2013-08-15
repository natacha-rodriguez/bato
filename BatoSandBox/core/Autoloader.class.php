<?php

class Autoloader {

    private static $directories = array();

// attempt to autoload a specified class 
    public static function load($class) {
        if (class_exists($class, FALSE)) {
            return;
        }
        $directories = self::getDirectories();
        foreach ($directories as $dir) {
            $file = $dir . "/$class.class.php";
            if (file_exists($file)) {
                require_once $file;
                unset($file);
                return;
            } else {
                $file = $dir . "/$class.php";
                if (file_exists($file)) {
                    require_once $file;
                    unset($file);
                    return;
                }
            }
        }
        //throw new Exception("Class $class not found");
    }

    protected static function getDirectories() {
        if (empty(self::$directories)) {
            $mainPath = realpath(dirname(__FILE__) . '/../../');
            //echo "real path is $mainPath \n";
            $subdirs = self::getSubDirs($mainPath);
            //echo 'subdirs are ' . var_export($subdirs, true) . "\n";
            self::$directories = array_merge(array($mainPath), $subdirs);
            //echo 'directories gotten: ' . var_export(self::$directories, true) . "\n";
        }
        return self::$directories;
    }

    protected static function getSubDirs($path) {
        //echo 'getting dirs for path...' . var_export($path, true) . "\n";
        $dirs = glob($path . '/*', GLOB_ONLYDIR);
        //echo 'dirs are...' . var_export($dirs, true) . "\n";
        $finalDirs = $dirs;
        if (!empty($dirs)) {
            foreach ($dirs as $dir) {
                $temp = self::getSubDirs($dir);
                //echo 'this is the temp array so far...' . var_export($temp, true) . "\n";
                
                $finalDirs = array_merge((array)$finalDirs, (array)$temp);
                //echo 'final dirs look like this so far...' . var_export($finalDirs, true) . "\n";
            }
        }
        //echo 'final dirs to return...' . var_export($finalDirs, true) . "\n";
        return $finalDirs;
    }

}

?>
