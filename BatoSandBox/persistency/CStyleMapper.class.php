<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CStyleMapper
 *
 * @author naty
 */
class CStyleMapper implements Mapper {

    private static $setterPrefix = 'set_';
    private static $getterPrefix = 'get_';

    /**
     *
     * @var InflectorInterface 
     */
    private static $inflector;

    public static function init($inflector) {
        self::$inflector = $inflector;
    }

    /**
     *
     * @param object $object 
     */
    public static function getFields($object) {
        $fieldNames = self::getFieldNames($object);
        $fields = array();
        foreach ($fieldNames as $key => $value) {
            $method = array($object, self::$getterPrefix . $value);
            $fields[":$value"] = call_user_func($method);
        }

        return $fields;
    }

    /**
     *
     * @param  $object an object or a string with a classname
     * @return array $fieldNames
     */
    public static function getFieldNames($object) {
        $class = (is_string($object)) ? $object : get_class($object);
        $methods = get_class_methods($class);
        $externalFields = null;
        if (method_exists($class, 'get_external_fields')) {
            $externalFields = $class::get_external_fields();
        }
//        Logger::log(__METHOD__ . '... externalFields are....' . var_export($externalFields, true));
        $fieldNames = array();
        foreach ($methods as $value) {
            if (strpos($value, self::$setterPrefix) === 0) {
                $fieldName = substr($value, strlen(self::$setterPrefix));
                if (is_null($externalFields) || !in_array($fieldName, $externalFields)) {
                    $fieldNames[] = $fieldName;
                }
            }
        }

        return $fieldNames;
    }

    public static function getTableName($object) {
        $class = (is_string($object)) ? $object : get_class($object);
        $words = preg_split('/([[:upper:]][[:lower:]]+)/', $class, null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        $tableName = self::$inflector->pluralize($words);
        $tableName = strtolower(implode('_', $tableName));

        return $tableName;
    }

    public static function getSetter($fieldName) {
        return self::$setterPrefix . $fieldName;
    }

    public static function getGetter($fieldName) {
        return self::$getterPrefix . $fieldName;
    }

}

?>
