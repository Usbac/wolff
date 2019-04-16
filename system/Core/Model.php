<?php

namespace Core;

use System\Library\Easql;

class Model {

    /**
     * Loader.
     *
     * @var Core\Loader
     */
    protected $load;
    protected $library;

    /**
     * Session manager.
     *
     * @var Core\Session
     */
    protected $session;

    /**
     * Cache system.
     *
     * @var Core\Cache
     */
    protected $cache;

    /**
     * Static instance of the connection.
     *
     * @var Core\Connection
     */
    protected $db;

    /**
     * Query builder utility.
     *
     * @var System\Library\Easql
     */
    protected $easql;

    public function __construct($loader) {
        $this->load = &$loader;
        $this->session = $this->load->getSession();
        $this->cache = $this->load->getCache();
        $this->db = $this->load->getDB();
        $this->easql = new Easql($this->db);
    }

}