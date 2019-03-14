<?php

namespace System;

/**
 * @property Loader load
 * @property Library library
 * @property Session session
 * @property Data data
 * @property Cache cache
 * @property Upload upload
 */

class Controller {

    public function __construct($load, $library, $session, $cache, $upload) {
        $this->load = $load;
        $this->library = $library;
        $this->session = $session;
        $this->cache = $cache;
        $this->upload = $upload;
        $this->data = array();
    }

}