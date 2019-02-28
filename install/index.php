<?php

class Install {

    public function __construct() {
        include('../system/loader.php');
        include('../system/library.php');
        include(__DIR__ . '/controller/install.php');

        $load = new Loader();
        $library = new Library();
        $controller = new Controller_install;
        
        if (@end(explode('/', $_GET['url'])) == 'save') {
            $controller->save();
        } else {
            $controller->index();
        }
    }

}

$index = new Install();