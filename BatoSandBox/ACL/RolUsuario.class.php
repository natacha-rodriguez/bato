<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RolUsuario
 *
 * @author naty
 */
class RolUsuario {

    private $id;
    private $id_rol;
    private $id_usuario;

    public function get_id() {
        return $this->id;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function get_id_rol() {
        return $this->id_rol;
    }

    public function set_id_rol($id_rol) {
        $this->id_rol = $id_rol;
    }

    public function get_id_usuario() {
        return $this->id_usuario;
    }

    public function set_id_usuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    /**
     *
     * @return Rol 
     */
    public function get_rol() {
        $rol = Context::getPM()->getById('Rol', $this->get_id_rol());
        return $rol;
    }

}

?>
