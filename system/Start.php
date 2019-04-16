<?php

namespace System;

use Core\{Session, Route, Cache, Loader, Extension, Connection};
use System\Library\{Upload, Maintenance};

class Start {

    public $extension;

    /**
	 * Loader.
	 *
	 * @var Core\Loader
	 */
    public $load;

    /**
	 * Static instance of the connection.
	 *
	 * @var Core\Connection
	 */
    public $db;

    /**
	 * Session manager.
	 *
	 * @var Core\Session
	 */
    public $session;

    /**
	 * Cache system.
	 *
	 * @var Core\Cache
	 */
    public $cache;

    /**
	 * File uploader utility.
	 *
	 * @var System\Library\Upload
	 */
    public $upload;


    /**
     * Start the loading of the page
     */
    public function __construct() {
        $this->initComponents();

        $test = array(
            array(
                'nombre'  => 'Alejandro',
                'apellido'=> 'castillo',
                'edad'    => 21
            ), array(
                'nombre'  => 'Jose',
                'apellido'=> 'lmao',
                'edad'    => 19
            )
        );

        arrayToCsv('testing', $test);

        //Check maintenance mode
        if (maintenanceEnabled() && !Maintenance::isClientAllowed()) {
            $this->load->maintenance();
        }

        $url = sanitizeURL($_GET['url']?? getMainPage());

        //Check blocked route
        if (Route::isBlocked($url)) {
            $this->load->redirect404();
        }

        //Load extensions
        if (extensionsEnabled()) {
            $this->extension = new Extension($this->load);
            $this->extension->load();
        }

        $function = Route::get($url);

        if (isset($function)) {
            $function->call($this);
        } elseif (controllerExists($url) || functionExists($url)) {
            $this->load->controller($url);
        } else {
            $this->load->redirect404();
        }
    }


    /**
     * Initialize the main components
     */
    public function initComponents() {
        $this->session = new Session();
        $this->cache = new Cache();
        $this->upload = new Upload();
        $this->db = Connection::getInstance(WOLFF_DBMS);
        $this->load = new Loader($this->session, $this->cache, $this->upload, $this->db);
    }

}