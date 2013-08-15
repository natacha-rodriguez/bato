<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author naty
 */
class User {
    protected  $roles_usuario;
    
    protected static $externalFields = array('roles_usuario');
    
    public function get_roles_usuario() {
        if(is_null($this->roles_usuario)){
            $this->roles_usuario = Context::getPM()->getByForeignKey('RolUsuario', 'id_usuario', $this->get_id());
        }
        return $this->roles_usuario;
    }

    public function set_roles_usuario($roles_usuario) {
        $this->roles_usuario = $roles_usuario;
    }
    
    public function get_ids_roles(){
        $roles = $this->get_roles_usuario();
        $ids = array();
        /* @var $r Rol */
        foreach($roles as $r){
            $ids[] = $r->get_id_rol();
        }
        Logger::log(__METHOD__.'... ids...'. var_export($ids, true));
        return $ids;
    }
    
    public static function get_external_fields(){
        return self::$externalFields;
    }

}

?>
