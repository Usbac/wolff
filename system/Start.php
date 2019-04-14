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
        } else if (controllerExists($url) || functionExists($url)) {
            $this->load->controller($url);
        } else {
            $this->load->redirect404();
        }
    }

}