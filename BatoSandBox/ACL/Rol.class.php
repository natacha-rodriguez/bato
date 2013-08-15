<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Rol
 *
 * @author naty
 */
class Rol {
    private $id;
    private $rol;
    
    public function get_id() {
        return $this->id;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function get_rol() {
        return $this->rol;
    }

    public function set_rol($rol) {
        $this->rol = $rol;
    }


}

?>
