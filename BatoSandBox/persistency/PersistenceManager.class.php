<?php

/**
 * Description of PersistenceManager
 *
 * @author naty
 */
class PersistenceManager {

    /**
     *
     * @var PDO 
     */
    protected static $db;

    /**
     *
     * @var Mapper 
     */
    protected static $mapper;

    /**
     *
     * @param PDO $db 
     */
    public static function init($db, $mapper) {
        //self::$db = $db;
        //self::$mapper = $mapper;
        self::$db = Context::getDB();
        self::$mapper = Context::getMapper();
    }

    /**
     *
     * @param type $object 
     */
    public static function add(&$object) {
        $st = self::getPreparedInsert($object);

        $fields = self::$mapper->getFields($object);
        unset($fields[':id']);  //new object, doesn't have id

        $success = $st->execute($fields);
        if (!$success) {
            Logger::log(__METHOD__ . '... ERRORRRRRRR....' . var_export($st->errorInfo(), true));
        } else {
            $method = array($object, self::$mapper->getSetter('id'));
            call_user_func($method, self::$db->lastInsertId());
        }
    }

    /**
     *
     * @param array $objects 
     */
    public static function addMany($objects) {
        $fields = self::$mapper->getFieldNames($objects[0]);
        $st = self::getPreparedInsert($objects[0]);
        foreach ($objects as $thisObject) {
            $fieldValues = self::$mapper->getFields($thisObject);
            unset($fieldValues[':id']);
            $success = $st->execute($fieldValues);
            if (!$success) {
                Logger::log(__METHOD__ . '... ERRORRRRRRR....' . var_export($st->errorInfo(), true));
                throw new Exception($st->errorInfo(), $st->errorInfo());
            } else {
                $method = array($thisObject, self::$mapper->getSetter('id'));
                call_user_func($method, self::$db->lastInsertId());
            }
        }
    }

    public static function update($object) {
        $method = array($object, self::$mapper->getGetter('id'));
        $id = call_user_func($method);
        $table = self::$mapper->getTableName($object);
        $fieldValues = self::$mapper->getFields($object);

        $sql = "update $table set ";
        $temp = array();
        foreach ($fieldValues as $selector => $value) {
            $temp[$selector] = substr($selector, 1) . " = $selector";
        }
        unset($temp[':id']); // the id is used in the where clause
        $st = self::$db->prepare($sql . join(', ', $temp) . " where id = :id ");
        $st->execute($fieldValues);
    }

    /**
     *
     * @param type $object 
     */
    public static function delete($object) {
        $sql = 'delete from ' . self::$mapper->getTableName($object) . ' where id = :id';
        $st = self::$db->prepare($sql);
        $methodName = self::$mapper->getGetter('id');
        $method = array($object, $methodName);
        if (method_exists($object, $methodName)) {
            $success = $st->execute(array(':id' => call_user_func($method)));
            if(!$success){
                throw new Exception('Errrrroooooorrr no pudo borrar el objeto.... '. var_export($st->errorInfo(), true));
            }
        } else {
            throw new Exception('Errrrroooooorrr el metodo ' . var_export($method, true) . ' no existe', null);
        }
    }

    /**
     *
     * @param string $class
     * @return array 
     */
    public static function selectAll($class) {
        $sql = "select * from " . self::$mapper->getTableName($class);
        $st = self::$db->prepare($sql);
        $st->execute();
        $objects = $st->fetchAll(PDO::FETCH_CLASS | pdo::FETCH_PROPS_LATE, $class);

        return $objects;
    }

    /**
     *
     * @param string $class
     * @param int $id
     * @return $class of the specified class 
     */
    public static function getById($class, $id) {
        $sql = "select * from " . self::$mapper->getTableName($class) . ' where id = :id';
        $st = self::$db->prepare($sql);
        $st->execute(array(':id' => $id));
        $st->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);
        $object = $st->fetch();

        return $object;
    }

    public static function getByForeignKey($class, $foreignKey, $value) {
        $tableName = self::$mapper->getTableName($class);
        $sql = "select * from $tableName where $foreignKey = :$foreignKey";
        $st = self::$db->prepare($sql);
        $st->execute(array(":$foreignKey" => $value));
        $objects = $st->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);

        return $objects;
    }

    /**
     *
     * @param object $object
     * @return PDOStatement 
     */
    private static function getPreparedInsert($object) {
        $table = self::$mapper->getTableName($object);
        $names = self::$mapper->getFieldNames($object);
        $key = array_search('id', $names);
        unset($names[$key]); //if it's insert, it doesn't have the id yet
        $sql = "insert into $table ("
                . join(', ', $names) . ") values ( :"
                . join(", :", $names)
                . " )";
        $st = self::$db->prepare($sql);

        return $st;
    }

}

?>
