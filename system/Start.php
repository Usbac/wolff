<?php

namespace System;

use Core\{Session, Route, Cache, Loader, Extension};
use System\Library\{Upload, Maintenance};

class Start {

    public $extension;
    public $load;


    /**
     * Start the loading of the page
     */
    public function __construct() {
        $this->load = new Loader(new Session(), new Cache(), new Upload());
        $this->extension = new Extension($this->load);
        
        $url = sanitizeURL($_GET['url']?? getMainPage());

        //Check maintenance mode
        if (maintenanceEnabled() && !Maintenance::isClientAllowed()) {
            $this->load->maintenance();
        }
        
        //Check block
        if (Route::isBlocked($url)) {
            $this->load->redirect404();
        }

        $function = Route::get($url);

        if (isset($function)) {
            $this->extension->load();
            $function->call($this);
        } else if (controllerExists($url) || functionExists($url)) {
            $this->extension->load();
            $this->load->controller($url);
        } else {
            $this->load->redirect404();
        }
    }

}