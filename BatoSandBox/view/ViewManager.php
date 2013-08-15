<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author naty
 */
interface ViewManager {
    public static function init($settings);
    public static function parseView($filename, $data=null);
}

?>
