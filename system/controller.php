<?php

namespace System;

/**
 * @property Loader load
 * @property Library library
 * @property Session session
 * @property Data data
 * @property Cache cache
 */

class Controller {

    public function __construct($loader, $library, $session, $cache) {
        $this->load = $loader;
        $this->library = $library;
        $this->session = $session;
        $this->cache = $cache;
        $this->data = array();
    }

}