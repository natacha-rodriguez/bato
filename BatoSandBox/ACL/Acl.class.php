<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Acl
 *
 * @author naty
 */
class Acl {

    protected static $separator = '/';

    public function authorize($areaName, $idUser) {
        $passes = false;
        if (is_null($idUser)) {
            // ver si el area o su area padre es publica
            $areaParts = explode(self::$separator, $areaName);
            logger::log(__METHOD__.'... areaname... '. $areaName.'....areaparts...'. var_export($areaParts, true).'... id user...'. var_export($idUser, true));
            $sql = "select a.area, a.privada from areas a where a.privada = 0 and a.area in (:areaName, :parentAreaName)";
            $params = array(':areaName'=>$areaName, ':parentAreaName'=> $areaParts[0]);
            $st = Context::getDB()->prepare($sql);
            $st->execute($params);
            $aFound = $st->fetchAll(PDO::FETCH_ASSOC);
            //Logger::log(__METHOD__.'...before the foreach...'. var_export($aFound, true));
            foreach($aFound as $aName){
                Logger::log(__METHOD__.'...in the foreach...');
                if($aName['area'] == $areaName){ //restrictive specs...
              //      Logger::log(__METHOD__.'..found restrictive specs...');
                    $passes = true;
                    break;
                }else if($aName['area']== $areaParts[0]){ // general specs...
                //    Logger::log(__METHOD__.'..found general specs...');
                    $passes = true;
                    continue;
                }
            }
        } else {
            $sql = "select a.area, p.permiso from permisos p
                    inner join roles_usuarios ru on ru.id_rol = p.id_rol
                    inner join areas a on a.id = p.id_area
                    where ru.id_usuario = :idUsuario
            ";
            $st = Context::getDB()->prepare($sql);
            $st->execute(array(':idUsuario' => $idUser));
            $accesses = $st->fetchAll(PDO::FETCH_ASSOC);
            foreach ($accesses as $row) {
                if ($row['area'] == '*') {
                    $passes = $row['permiso'];
                    continue; //check for more restrictive specs
                } elseif ($row['area'] == $areaName) {
                    $passes = $row['permiso'];
                    break; //found restrictive specs, no need to search further
                }
                //it only gets here if didn't match so far
                $areaParts = explode(self::$separator, $areaName);
                if ($areaParts[0] == $row['area']) {
                    //has access to the entire area... take it for now but keep looking...
                    $passes = $row['permiso'];
                    continue;
                }
            }
        }
        return (boolean) $passes;
    }

}

?>
