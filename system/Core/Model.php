<?php

namespace Core;

use System\Library\Easql;

class Model {

    protected $load;
    protected $library;
    protected $session;
    protected $db;
    protected $cache;
    protected $easql;

    public function __construct($loader, $session, $cache) {
        $this->load = &$loader;
        $this->session = &$session;
        $this->cache = &$cache;
        $this->db = Connection::getInstance(WOLFF_DBMS);
        $this->easql = new Easql($this->db);
    }

}