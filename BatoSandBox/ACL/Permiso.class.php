<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Permiso
 *
 * @author naty
 */
class Permiso {

    private $id;
    private $id_rol;
    private $id_area;
    private $permiso;

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

    public function get_id_area() {
        return $this->id_area;
    }

    public function set_id_area($id_area) {
        $this->id_area = $id_area;
    }

    public function get_permiso() {
        return $this->permiso;
    }

    public function set_permiso($permiso) {
        $this->permiso = $permiso;
    }

}

?>
