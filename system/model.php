<?php

namespace System;

/**
 * @property Loader load
 * @property Library library
 * @property Session session
 * @property Connection db
 */

class Model {

    public function __construct($loader, $library, $session, $dbms) {
        $this->load = $loader;
        $this->library = $library;
        $this->session = $session;
        $this->db = Connection::connect($dbms);
    }

}