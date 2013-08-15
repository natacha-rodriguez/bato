<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author naty
 */
interface InflectorInterface {
    public static function singularize($word);
    public static function pluralize($word);
}

?>
