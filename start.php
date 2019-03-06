<?php

namespace Root;
use System as Sys;

class Start {

    public $library;
    public $session;
    public $load;

    /**
     * Start the loading of the page
     */
    public function __construct() {
        $this->library = new Sys\Library();
        $this->session = new Sys\Session();
        $this->cache = new Sys\Cache();
        $this->load = new Sys\Loader($this->library, $this->session, $this->cache, DBMS);

        $url = Sys\Library::sanitizeURL($_GET['url']?? MAIN_PAGE);

        if (Sys\Route::isBlocked($url)) {
            $this->load->redirect404();
        }

        $function = Sys\Route::get($url);

        if (isset($function)) {
            call_user_func($function->bindTo($this));
        } else if ($this->library->controllerExists($url) || $this->library->functionExists($url)) {
            $this->load->controller($url);
        } else {
            $this->load->redirect404();
        }
    }

}