<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author naty
 */
interface Mapper {
    /**
     * @param InflectorInterface $inflector
     */
    public static function init($inflector);
    public static function getFieldNames($object);
    public static function getFields($object);
    public static function getTableName($object);
    public static function getSetter($fieldName);
    public static function getGetter($fieldName);
}

?>
