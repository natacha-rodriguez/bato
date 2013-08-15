<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Area
 *
 * @author naty
 */
class Area {

    private $id;
    private $area;

    public function get_id() {
        return $this->id;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function get_area() {
        return $this->area;
    }

    public function set_area($area) {
        $this->area = $area;
    }

}

?>
