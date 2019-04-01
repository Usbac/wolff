<?php

namespace Cli;

use System as Sys;

class Lister {
    private $route;
    private $extension;
    private $app_dir;
    private $public_dir;
    private $args;


    public function __construct($route, $extension, $app_dir, $public_dir)  {
        $this->route = &$route;
        $this->extension = &$extension;
        $this->app_dir = $app_dir;
        $this->public_dir = $public_dir;
    }

    
    public function index($args) {
        $this->args = $args;

        switch($this->args[1]) {
            case 'extensions':
                $this->extensions();
                break;
            case 'views':
                $this->views();
                break;
            case 'controllers':
                $this->controllers();
                break;
            case 'models':
                $this->models();
                break;
            case 'libraries':
                $this->libraries();
                break;
            case 'languages':
                $this->languages();
                break;
            case 'public':
                $this->public();
                break;
            case 'config':
                $this->config();
                break;
            default:
                echo "WARNING: Command doesn't exists \n \n";
                break;
        }
    }

    
    private function extensions() {
        $this->extension->load();
        $extensions = $this->extension->get();

        foreach ($extensions as $ext) {
            echo "\n -> " . $ext['name'];
            echo "\nDescription: " . $ext['description'];
            echo "\nVersion: " . $ext['version'];
            echo "\nAuthor: " . $ext['author'];
            echo "\nFilename: " . $ext['filename'];
            echo "\n";
        }
        echo "\n";
    }


    private function views() {
        $views = $this->listViewFiles($this->app_dir . 'view');

        foreach ($views as $view) {
            echo "\n" . $view;
        }
        echo "\n \n";
    }


    private function controllers() {
        $controllers = $this->listPHPFiles($this->app_dir . 'controller');

        foreach ($controllers as $controller) {
            echo "\n" . $controller;
        }
        echo "\n \n";
    }


    private function models() {
        $models = $this->listPHPFiles($this->app_dir . 'model');

        foreach ($models as $model) {
            echo "\n" . $model;
        }
        echo "\n \n";
    }


    private function libraries() {
        $libraries = $this->listPHPFiles($this->app_dir . 'library');

        foreach ($libraries as $library) {
            echo "\n" . $library;
        }
        echo "\n \n";
    }


    private function languages() {
        $languages = glob($this->app_dir . 'language' . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);

        foreach ($languages as $language) {
            echo "\n" . substr($language, strrpos($language, DIRECTORY_SEPARATOR)+1);
        }
        echo "\n \n";
    }


    private function public() {
        $files = $this->listAnyFiles($this->public_dir);

        foreach ($files as $file) {
            echo "\n" . $file;
        }
        echo "\n \n";
    }


    private function listViewFiles($dir, $folder = '', &$result = array()) {
        $folder = substr($dir, strrpos(str_replace('/', DIRECTORY_SEPARATOR, $dir), DIRECTORY_SEPARATOR) + 1);
        $files = scandir($dir);
        
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);

            if (!is_dir($path) && in_array(pathinfo($path)['extension'], array('php', 'html', 'phtml'))) {
                $file_path = substr($path, strpos($path, $folder) + strlen($folder) + 1);
                $result[] = $file_path;
            } else if ($value != "." && $value != "..") {
                $this->listFiles($path, $result);
            }
        }

        return $result;
    }


    private function listPHPFiles($dir, $folder = '', &$result = array()) {
        $folder = substr($dir, strrpos(str_replace('/', DIRECTORY_SEPARATOR, $dir), DIRECTORY_SEPARATOR) + 1);
        $files = scandir($dir);
        
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);

            if (!is_dir($path) && pathinfo($path)['extension'] == 'php') {
                $file_path = substr($path, strpos($path, $folder) + strlen($folder) + 1);
                $result[] = $file_path;
            } else if ($value != "." && $value != "..") {
                $this->listPHPFiles($path, $folder, $result);
            }
        }

        return $result;
    }


    private function listAnyFiles($dir, &$result = array()) {
        $files = scandir($dir);

        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);

            if (!is_dir($path)) {
                $file_path = $path;
                $result[] = $file_path;
            } else if ($value != "." && $value != "..") {
                $this->listAnyFiles($path,  $result);
            }
        }

        return $result;
    }


    private function config() {
        echo "\n -> SERVER CONFIG: ";
        echo "\n DBMS: " . DBMS;
        echo "\n Server: " . SERVER;
        echo "\n Database: " . DB;
        echo "\n User: " . USER;
        echo "\n Password: " . PASSWORD;
        echo "\n";
        echo "\n -> GENERAL CONFIG: ";
        echo "\n Project folder: " . PROJECT_ROOT;
        echo "\n App folder: " . APP;
        echo "\n Public folder: " . PUBLIC_DIR;
        echo "\n Page title: " . PAGE_TITLE;
        echo "\n Main page: " . MAIN_PAGE;
        echo "\n Language: " . LANGUAGE;
        echo "\n Extensions enabled: " . (EXTENSIONS? "yes":"no");
        echo "\n \n";
    }

}