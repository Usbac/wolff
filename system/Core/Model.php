<?php

namespace Core;

use System\Library\Easql;

class Model {

    protected $load;
    protected $library;
    protected $session;
    protected $cache;
    protected $db;
    protected $easql;

    public function __construct($loader) {
        $this->load = &$loader;
        $this->session = $this->load->getSession();
        $this->cache = $this->load->getCache();
        $this->db = Connection::getInstance(WOLFF_DBMS);
        $this->easql = new Easql($this->db);
    }

}