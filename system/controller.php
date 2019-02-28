<?php

/**
 * @property Loader load
 * @property Library library
 * @property Session session
 * @property Data data
 */

class Controller {

    public function __construct() {
        $this->load = new Loader();
        $this->library = new Library();
        $this->session = new Session;
        $this->data = array();
    }

}