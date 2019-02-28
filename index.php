<?php
include('config.php');

class Index {

    public function __construct() {
        chdir(dirname(__FILE__));
        session_start();
        
        $this->loadSystemFiles();
        $this->checkInstallation();

        $this->load = new Loader();
        $this->session = new Session();

        $url = Library::sanitizeURL($_GET['url']?? MAIN_PAGE);

        if (Route::isBlocked($url)) {
            $this->load->redirect404();
        }

        $function = Route::get($url);

        if (isset($function)) {
            $function();
        } else if (Library::controllerExists($url) || Library::ControllerFuncExists($url)) {
            $this->load->controller($url);
        } else {
            $this->load->redirect404();
        }

    }


    /**
     * Loads all the php files in the System folder
     */
    public function loadSystemFiles() {
        $system_folder = 'system/';
        include_once($system_folder . 'library.php');
        include_once($system_folder . 'loader.php');
        include_once($system_folder . 'session.php');
        include_once($system_folder . 'route.php');
        include_once($system_folder . 'routes.php');
        include_once($system_folder . 'connection.php');
        include_once($system_folder . 'controller.php');
        include_once($system_folder . 'model.php');
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