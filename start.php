<?php

class Start {

    public $library;
    public $session;
    public $load;

    /**
     * Start the loading of the page
     */
    public function __construct() {
        $this->library = new System\Library();
        $this->session = new System\Session();
        $this->load = new System\Loader($this->library, $this->session);

        $url = System\Library::sanitizeURL($_GET['url']?? MAIN_PAGE);

        if (System\Route::isBlocked($url)) {
            $this->load->redirect404();
        }

        $function = System\Route::get($url);

        if (isset($function)) {
            call_user_func($function->bindTo($this));
        } else if ($this->library->controllerExists($url) || $this->library->functionExists($url)) {
            $this->load->controller($url);
        } else {
            $this->load->redirect404();
        }
    }

}