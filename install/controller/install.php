<?php

class Controller_install {

    public function index() {
        $data['error'] = '';
        $data['title'] = 'installation';
        include(__DIR__ . '/../view/install.html');
    }


    public function save() {
        $data['error'] = $this->checkErrors($_POST);

        if (empty($data['error'])) {
            $this->writeToFile($_POST);
        } else {
            $data['title'] = 'installation';
            include(__DIR__ . '/../view/install.html');
        }   
    }


    public function checkErrors($data) {

        if (empty($data['title'])) {
            return 'Error: page title required';
        }

        include(__DIR__ . '/../model/install.php');
        $model = new Model_install;

        if ((isset($data['db']) || isset($data['username'])) && $model->error($data)) {
            return 'Error: Database connection failed. Check your info or leave the connection fields empty';
        }

        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/' . $data['directory'])) {
            return 'Error: ' . $data['directory'] . ' is not a directory';
        }

        return '';
    }
    

    public function writeToFile($data) {
        if (empty($data['directory']) || $data['directory'] == '/') {
            $data['directory'] = '/';
        } else {
            $data['directory'] = '/' . $data['directory'] . '/';
        }

        $content  = "<?php \n";
        $content .= "//Wolff v0.1 \n \n";
        $content .= "//Server \n";
        $content .= "define('SERVER', '" . $data['host'] . "'); \n";
        $content .= "define('DB', '" . $data['db'] . "'); \n";
        $content .= "define('USER', '" . $data['username'] . "'); \n";
        $content .= "define('PASSWORD', '" . $data['password'] . "'); \n \n";
        $content .= "//General \n";
        $content .= "define('PAGE_TITLE', '" . $data['title'] . "'); \n";
        $content .= "define('MAIN_PAGE', 'home'); \n";
        $content .= "define('LANGUAGE', '" . $data['language'] . "'); \n";
        $content .= "define('DIR', '" . $data['directory'] . "'); \n";
        $content .= "define('MAIN', 'main/'); \n";

        $file_path = $_SERVER['DOCUMENT_ROOT'] . '/' . $data['directory'] . '/config.php';
        $file = fopen($file_path, 'w');
        fwrite($file, $content);
        fclose($file);
        
        if (file_exists($file_path)) {
            $_GET['url'] = 'main_page';
            include(__DIR__ . '/../../index.php');
        }
    }

}