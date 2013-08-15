<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Context
 *
 * @author naty
 */
class Context {

    private static $contextSpecs = array();
    private static $context = array();

    public static function init() {
        //reads general context
        $json = file_get_contents(dirname(__FILE__) . '/../context.json');
        $content = json_decode($json, true);
        //reads local context

        self::$contextSpecs = $content;
        
        Logger::init($content['logger']['logFile'], $content['logger']['debugLevel']);
    }

    /**
     * @return PersistenceManager 
     */
    public static function getPM() {

        if (!isset(self::$context['persistenceManager'])) {
            $db = self::getDB();
            $mapper = self::getMapper();
            $pm = new self::$contextSpecs['persistenceManager']();
            $pm->init($db, $mapper);
            self::$context['persistenceManager'] = $pm;
        }
        return self::$context['persistenceManager'];
    }

    /**
     *
     * @return Mapper 
     */
    public static function getMapper() {
        if (!isset(self::$context['mapper'])) {
            $inflector = self::getInflector();
            $mapper = new self::$contextSpecs['mapper']();
            $mapper->init($inflector);
            self::$context['mapper'] = $mapper;
        }
        return self::$context['mapper'];
    }

    /**
     * @return InflectorInterface
     */
    public static function getInflector() {
        if (!isset(self::$context['inflector'])) {
            $inflector = new self::$contextSpecs['inflector']();
            self::$context['inflector'] = $inflector;
        }
        return self::$context['inflector'];
    }

    /**
     *
     * @return PDO 
     */
    public static function getDB() {
        if (!isset(self::$context['db'])) {
            $db = new PDO(
                            self::$contextSpecs['db']['string'],
                            self::$contextSpecs['db']['username'],
                            self::$contextSpecs['db']['password']);
            self::$context['db'] = $db;
        }
        return self::$context['db'];
    }
    
    /**
     *
     * @return ViewManager
     */
    public static function getViewManager(){
        if(!isset (self::$context['viewManager'])){
            $viewManager = new self::$contextSpecs['viewManager']['class']();
            $viewManager->init(self::$contextSpecs['viewManager']['settings']);
            self::$context['viewManager'] = $viewManager;
        }
        return self::$context['viewManager'];
    }

}

?>
