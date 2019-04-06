<?php

namespace Core;

class Controller {

    protected $load;
    protected $library;
    protected $session;
    protected $data;
    protected $cache;
    protected $upload;
    protected $extension;

    public function __construct($load, $session, $cache, $upload, $extension) {
        $this->load = &$load;
        $this->session = &$session;
        $this->cache = &$cache;
        $this->upload = &$upload;
        $this->extension = &$extension;
        $this->data = array();
    }

}