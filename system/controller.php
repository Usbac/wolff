<?php

namespace System;

/**
 * @property Loader load
 * @property Library library
 * @property Session session
 * @property Data data
 */

class Controller {

    public function __construct($loader, $library, $session) {
        $this->load = $loader;
        $this->library = $library;
        $this->session = $session;
        $this->data = array();
    }

}