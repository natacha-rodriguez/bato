<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewManager
 *
 * @author naty
 */
class SmartyViewManager implements ViewManager {

    /**
     *
     * @var Smarty 
     */
    private static $smarty;
    private static $templatesDir;
    private static $templatesCDir;
    private static $cacheDir;
    private static $configs;

    public static function init($settings) {
        self::$smarty = new Smarty();
        self::$templatesDir = $settings['templatesDir'];
        self::$templatesCDir = $settings['templatesCDir'];
        self::$cacheDir = $settings['cacheDir'];
        self::$configs = $settings['configs'];
        self::$smarty->setTemplateDir(self::$templatesDir);
        self::$smarty->setCompileDir(self::$templatesCDir);
        self::$smarty->setConfigDir(self::$configs);
        self::$smarty->setCacheDir(self::$cacheDir);
    }

    public static function parseView($filename, $data = null) {
        return self::$smarty->fetch($filename, $data);
    }

}

?>
