<?php

namespace Core;

class Controller {

    protected $load;
    protected $session;
    protected $data;
    protected $cache;
    protected $upload;

    public function __construct($load) {
        $this->load = &$load;
        $this->session = $this->load->getSession();
        $this->cache = $this->load->getCache();
        $this->upload = $this->load->getUpload();
        $this->data = array();
    }

}