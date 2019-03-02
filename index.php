<?php
include('config.php');

class Index {

    public function __construct() {
        chdir(dirname(__FILE__));
        session_start();
        
        $this->loadSystemFiles();
        //$this->checkInstallation();

        $this->load = new Loader();
        $this->session = new Session();

        $url = Library::sanitizeURL($_GET['url']?? MAIN_PAGE);

        if (Route::isBlocked($url)) {
            $this->load->redirect404();
        }

        $function = Route::get($url);

        if (isset($function)) {
            $function();
        } else if (Library::controllerExists($url) || Library::functionExists($url)) {
            $this->load->controller($url);
        } else {
            $this->load->redirect404();
        }

    }


    /**
     * Loads all the php files in the System folder
     */
    public function loadSystemFiles() {
        $files = glob('system/*.php');

        foreach($files as $file) {
            if (is_file($file)) {
                include_once($file);
            }
        }
    }


    /**
     * Redirect to the installation page if the install folder exists
     */
    public function checkInstallation() {
        if (is_dir('install') && !Library::strContains($_GET['url'], 'home')) {
            header('Location: install');
        }
    }

}

$index = new Index();