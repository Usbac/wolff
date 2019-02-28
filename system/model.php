<?php

/**
 * @property Loader load
 * @property Library library
 * @property Session session
 * @property db db
 */

class Model {

    public function __construct() {
        $this->load = new Loader();
        $this->library = new Library();
        $this->session = new Session();
        $this->db = Connection::connect();
    }

}