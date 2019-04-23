<?php

namespace System;

use Core\{DB, Extension, Loader, Route, Session};
use Utilities\{Maintenance, Upload};

class Start
{

    /**
     * Extension.
     *
     * @var Core\Extension
     */
    public $extension;

    /**
     * Loader.
     *
     * @var Core\Loader
     */
    public $load;

    /**
     * Session manager.
     *
     * @var Core\Session
     */
    public $session;

    /**
     * File uploader utility.
     *
     * @var Utilities\Upload
     */
    public $upload;


    /**
     * Start the loading of the page
     */
    public function __construct() {
        $this->initComponents();

        //Check maintenance mode
        if (maintenanceEnabled() && !Maintenance::isClientAllowed()) {
            $this->load->maintenance();
        }

        $url = sanitizeURL($_GET['url'] ?? getMainPage());

        //Load extensions of type before
        if (extensionsEnabled()) {
            Extension::load('before', $this->load);
        }

        //Check blocked route
        if (Route::isBlocked($url)) {
            $this->load->redirect404();
        }

        $function = Route::get($url);

        if (isset($function)) {
            $function->call($this);
        } elseif (controllerExists($url) || functionExists($url)) {
            $this->load->controller($url);
        } else {
            $this->load->redirect404();
        }

        //Load extensions of type after
        if (extensionsEnabled()) {
            Extension::load('after', $this->load);
        }
    }


    /**
     * Initialize the main components
     */
    public function initComponents() {
        DB::initialize();
        $this->session = new Session();
        $this->upload = new Upload();
        $this->load = new Loader($this->session, $this->upload);
    }

}