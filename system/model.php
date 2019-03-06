<?php

namespace System;

/**
 * @property Loader load
 * @property Library library
 * @property Session session
 * @property Connection db
 * @property Cache cache
 */

class Model {

    public function __construct($loader, $library, $session, $dbms, $cache) {
        $this->load = $loader;
        $this->library = $library;
        $this->session = $session;
        $this->db = Connection::connect($dbms);
        $this->cache = $cache;
    }

}