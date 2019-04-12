<?php

namespace Cli;

use System as Sys;
use System\Library as Lib;

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
            case 'routes':
                $this->routes();
                break;
            case 'redirects':
                $this->redirects();
                break;
            case 'blocked':
                $this->blocked();
                break;
            case 'config':
                $this->config();
                break;
            case 'ip':
                $this->ip();
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
        $views = $this->listViewFiles($this->app_dir . 'views');

        foreach ($views as $view) {
            echo "\n" . $view;
        }
        echo "\n \n";
    }


    private function controllers() {
        $controllers = $this->listPHPFiles($this->app_dir . 'controllers');

        foreach ($controllers as $controller) {
            echo "\n" . $controller;
        }
        echo "\n \n";
    }


    private function models() {
        $models = $this->listPHPFiles($this->app_dir . 'models');

        foreach ($models as $model) {
            echo "\n" . $model;
        }
        echo "\n \n";
    }


    private function libraries() {
        $libraries = $this->listPHPFiles($this->app_dir . 'libraries');

        foreach ($libraries as $library) {
            echo "\n" . $library;
        }
        echo "\n \n";
    }


    private function languages() {
        $languages = glob($this->app_dir . 'languages' . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);

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


    private function routes() {
        $routes = Core\Route::getAvailableRoutes();

        if (count($routes) <= 0) {
            echo "\n ROUTES: none \n \n";
            return;
        } else {
            foreach ($routes as $key => $value) {
                echo "\n " . $key;
            }
        }
        
        echo "\n \n";
    }


    private function blocked() {
        $blocked = Core\Route::getBlockedRoutes();
        
        if (count($blocked) <= 0) {
            echo "\n BLOCKED: none \n \n";
            return;
        } else {
            foreach ($blocked as $key => $value) {
                echo "\n " . $key;
            }
        }

        echo "\n \n";
    }


    private function redirects() {
        $redirects = Core\Route::getRedirects();
        
        if (count($redirects) <= 0) {
            echo "\n REDIRECTIONS: none \n \n";
            return;
        } else {
            foreach ($redirects as $key => $value) {
                echo "\n " . $redirects[$key]['origin'] . " -> " .  $redirects[$key]['destiny'] . " | " .  $redirects[$key]['code'];
            }
        }

        echo "\n \n";
    }


    private function ip() {
        $ips = Lib\Maintenance::getAllowedIPs();

        if ($ips === false || count($ips) <= 0) {
            echo "\n Allowed IPs: none \n \n";
            return;
        } else {
            foreach ($ips as $ip) {
                echo "\n " . $ip;
            }
        }

        echo "\n \n"; 
    }


    private function config() {
        echo "\n -> WOLFF_SERVER CONFIG: ";
        echo "\n WOLFF_DBMS: " . WOLFF_DBMS;
        echo "\n Server: " . WOLFF_SERVER;
        echo "\n Database: " . WOLFF_DB;
        echo "\n User: " . DB_USER;
        echo "\n Password: " . WOLFF_DBPASSWORD;
        echo "\n";
        echo "\n -> GENERAL CONFIG: ";
        echo "\n Project folder: " . WOLFF_SYS_DIR;
        echo "\n App folder: " . WOLFF_APP_DIR;
        echo "\n Public folder: " . WOLFF_PUBLIC_DIR;
        echo "\n Page title: " . WOLFF_PAGE_TITLE;
        echo "\n Main page: " . WOLFF_MAIN_PAGE;
        echo "\n Language: " . WOLFF_LANGUAGE;
        echo "\n Extensions enabled: " . (SYS_EXTENSIONS? "yes":"no");
        echo "\n \n";
    }

}