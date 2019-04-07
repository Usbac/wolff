<?php

namespace System;

use System\Library as Lib;
use Core as Core;
use Core\Route;

class Start {

    public $library;
    public $session;
    public $cache;
    public $extension;
    public $load;
    public $upload;


    /**
     * Start the loading of the page
     */
    public function __construct() {
        $this->initProperties();
        
        $url = sanitizeURL($_GET['url']?? MAIN_PAGE);

        if (Route::isBlocked($url)) {
            $this->load->redirect404();
        }

        //Activate or deactivate the extensions
        $this->extension->activate(EXTENSIONS);

        $function = Route::get($url);

        if (isset($function)) {
            $this->extension->load();
            call_user_func($function->bindTo($this));
        } else if (controllerExists($url) || functionExists($url)) {
            $this->extension->load();
            $this->load->controller($url);
        } else {
            $this->load->redirect404();
        }
    }


    /**
     * Initialize all the properties
     */
    private function initProperties() {
        $this->session = new Core\Session();
        $this->cache = new Core\Cache();
        $this->upload = new Lib\Upload();
        $this->extension = new Core\Extension($this->session, $this->cache, $this->upload);
        $this->load = new Core\Loader($this->session, $this->cache, $this->upload, $this->extension);
    }

}