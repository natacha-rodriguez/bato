<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controller
 *
 * @author naty
 */
class Controller {

    protected $className;
    private $wwwPath;

    protected function redirect($route) {
        $url = $this->processRoute($route);
        header("Location: $url");
    }

    protected function processRoute($route) {
        $url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $url = array_slice($url, 0, 2);
        $url = implode('/', $url) . '/' . $route;
        return '/' . $url;
    }

    protected function fill(&$object, $data) {
        foreach ($data as $fieldName => $value) {
            $method = Context::getMapper()->getSetter($fieldName);
            if (method_exists($object, $method)) {
                $object->$method($value);
            }
        }
    }

    public function __call($name, $arguments) {
        $default = new DefaultController();
        return $default->$name();
    }

    public function all($parse = true) {
        $lista = Context::getPM()->selectAll($this->className);

        $data = array(
            'lista' => $lista,
            //'urlReserva' => $this->processRoute('reserva/leer'),
            'addAction' => $this->processRoute(strtolower($this->className) . '/nuevo'),
            'menu' => $this->getMenu(),
        );
        if ($parse === true) {
            $view = Context::getViewManager()->parseView(strtolower($this->className) . '/list.tpl', $data);
            return $view;
        } else {
            return $data;
        }
    }

    protected function getMenu() {
        $areas = array(
            'Inmuebles' => 'inmueble/todos',
            'Usuarios' => 'usuario/todos',
            'Cerrar Sesión' => 'index/logout',
        );
        $acl = new Acl();
        $menuLinks = array();
        foreach ($areas as $title => $area) {
            if ($acl->authorize($area, $_SESSION['userId'])) {
                $menuLinks[$title] = $this->processRoute($area);
            }
        }
        $view = Context::getViewManager()->parseView('snippets/menu.tpl', compact('menuLinks'));
        return $view;
    }

    /**
     *
     * @return Usuario 
     */
    protected function getLoggedUser() {
        $user = null;
        if (isset($_SESSION['userId'])) {
            $user = Context::getPM()->getById('Usuario', $_SESSION['userId']);
        }
        return $user;
    }

}

?>
