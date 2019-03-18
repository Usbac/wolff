<?php

namespace System;

/**
 * @property Loader load
 * @property Library library
 * @property Session session
 * @property Data data
 * @property Cache cache
 * @property Upload upload
 * @property Extension extension
 */

class Controller {

    public function __construct($load, $library, $session, $cache, $upload, $extension) {
        $this->load = &$load;
        $this->library = &$library;
        $this->session = &$session;
        $this->cache = &$cache;
        $this->upload = &$upload;
        $this->extension = &$extension;
        $this->data = array();
    }

}