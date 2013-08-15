<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Logger
 *
 * @author naty
 */
class Logger {

    private static $logFile;
    private static $debugLevel;

    public static function init($logFile, $debugLevel = array('error')) {
        self::$logFile = $logFile;
        self::$debugLevel = $debugLevel;
    }

    public static function log($message, $tag = 'debug') {
        if (in_array($tag, self::$debugLevel))
            error_log("[" . date('c') . "][$tag] $message \n", 3, self::$logFile);
    }

}

?>
