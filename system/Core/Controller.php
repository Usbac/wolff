<?php

namespace Core;

class Controller {

    protected $load;
    protected $session;
    protected $data;
    protected $cache;
    protected $upload;

    public function __construct($load, $session, $cache, $upload) {
        $this->load = &$load;
        $this->session = &$session;
        $this->cache = &$cache;
        $this->upload = &$upload;
        $this->data = array();
    }

}