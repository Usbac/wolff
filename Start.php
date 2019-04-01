<?php

namespace Root;

use System as Sys;
use System\Library as Lib;

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
        $this->initializeProperties();
        
        $url = Sys\Library::sanitizeURL($_GET['url']?? MAIN_PAGE);

        if (Sys\Route::isBlocked($url)) {
            $this->load->redirect404();
        }

        $function = Sys\Route::get($url);

        $this->extension->activate(EXTENSIONS);

        if (isset($function)) {
            $this->extension->load();
            call_user_func($function->bindTo($this));
        } else if ($this->library->controllerExists($url) || $this->library->functionExists($url)) {
            $this->extension->load();
            $this->load->controller($url);
        } else {
            $this->load->redirect404();
        }
    }


    /**
     * Initialize all the properties
     */
    private function initializeProperties() {
        $this->library = new Sys\Library();
        $this->session = new Sys\Session();
        $this->cache = new Sys\Cache();
        $this->upload = new Lib\Upload();
        $this->extension = new Sys\Extension($this->library, $this->session, $this->cache, $this->upload);
        $this->load = new Sys\Loader($this->library, $this->session, $this->cache, $this->upload, $this->extension, DBMS);
    }

}