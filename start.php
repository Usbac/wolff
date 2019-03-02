<?php

class Start {

    /**
     * Start the loading of the page
     */
    public function begin() {
        $this->load = new Loader();

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
    public static function loadSystemFiles() {
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
        if (is_dir('install') && !Library::strContains($_GET['url'], 'main_page')) {
            header('Location: install');
        }
    }

}
