<?php

namespace Cli;

use System\Library as Lib;

class Delete
{
    private $route;
    private $extension;
    private $app_dir;
    private $args;


    public function __construct($route, $extension, $app_dir) {
        $this->route = &$route;
        $this->extension = &$extension;
        $this->app_dir = $app_dir;
    }


    public function index($args) {
        $this->args = $args;
        $function = $this->args[1];

        if (method_exists($this, $function)) {
            $this->$function();
        } else {
            echo "WARNING: Command doesn't exists \n \n";
        }
    }


    private function page() {
        $this->controller();
        $this->model();
    }


    private function controller() {
        $file_dir = $this->app_dir . 'controllers' . DIRECTORY_SEPARATOR . $this->args[2] . '.php';

        if (!is_file($file_dir)) {
            echo "WARNING: the controller '" . $this->args[2] . "' doesn't exists! \n \n";
            return;
        }

        echo "Are you sure about deleting the " . $this->args[2] . ".php controller? Y/N \n";
        $response = readline(" -> ");
        if ($response == 'Y') {
            unlink($file_dir);
            echo "Controller " . $this->args[2] . " deleted successfully! \n \n";
        } else {
            echo "\n";
        }
    }


    private function model() {
        $file_dir = $this->app_dir . 'models' . DIRECTORY_SEPARATOR . $this->args[2] . '.php';

        if (!is_file($file_dir)) {
            echo "WARNING: the model '" . $this->args[2] . "' doesn't exists! \n \n";
            return;
        }

        echo "Are you sure about deleting the " . $this->args[2] . ".php model? Y/N \n";
        $response = readline(" -> ");
        if ($response == 'Y') {
            unlink($file_dir);
            echo "Model " . $this->args[2] . " deleted successfully! \n \n";
        } else {
            echo "\n";
        }
    }


    private function library() {
        $file_dir = $this->app_dir . 'libraries' . DIRECTORY_SEPARATOR . $this->args[2] . '.php';

        if (!is_file($file_dir)) {
            echo "WARNING: the library '" . $this->args[2] . "' doesn't exists! \n \n";
            return;
        }

        echo "Are you sure about deleting the " . $this->args[2] . ".php library? Y/N \n";
        $response = readline(" -> ");
        if ($response == 'Y') {
            unlink($file_dir);
            echo "Library " . $this->args[2] . " deleted successfully! \n \n";
        } else {
            echo "\n";
        }
    }


    private function view() {
        $file_dir = $this->app_dir . 'views' . DIRECTORY_SEPARATOR . $this->args[2];

        if (!is_file($file_dir)) {
            echo "WARNING: the view '" . $this->args[2] . "' doesn't exists! \n \n";
            return;
        }

        echo "Are you sure about deleting the " . $this->args[2] . " view? Y/N \n";
        $response = readline(" -> ");
        if ($response == 'Y') {
            unlink($file_dir);
            echo "View " . $this->args[2] . " deleted successfully! \n \n";
        } else {
            echo "\n";
        }
    }


    private function extension() {
        $file_dir = '..' . DIRECTORY_SEPARATOR . 'extension' . DIRECTORY_SEPARATOR . $this->args[2] . '.php';

        if (!is_file($file_dir)) {
            echo "WARNING: the extension '" . $this->args[2] . "' doesn't exists! \n \n";
            return;
        }

        echo "Are you sure about deleting the " . $this->args[2] . " extension? Y/N \n";
        $response = readline(" -> ");
        if ($response == 'Y') {
            unlink($file_dir);
            echo "Extension " . $this->args[2] . " deleted successfully! \n \n";
        } else {
            echo "\n";
        }
    }


    private function ip() {
        if (Lib\Maintenance::removeAllowedIP($this->args[2])) {
            echo "IP " . $this->args[2] . " removed successfully! \n \n";
        } else {
            echo "WARNING: IP " . $this->args[2] . " not removed! \n \n";
        }
    }


    private function language() {
        $language_dir = $this->app_dir . 'languages' . DIRECTORY_SEPARATOR . $this->args[2];
        $this->deleteRecursively($language_dir);
    }


    private function cache() {
        $cache_path = '..' . DIRECTORY_SEPARATOR . 'cache';

        if (!is_dir($cache_path)) {
            echo "WARNING: the cache folder doesn't exists! \n \n";
            return;
        }

        $files = glob($cache_path . DIRECTORY_SEPARATOR . '*');

        if (count($files) <= 0) {
            echo "WARNING: the cache folder is already empty! \n \n";
            return;
        }

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        echo "Cache deleted successfully! \n \n";
    }


    private function deleteRecursively($dir) {
        if (!is_dir($dir)) {
            echo "WARNING: the language '" . $this->args[2] . "' doesn't exists! \n \n";
            return;
        }

        if (substr($dir, strlen($dir) - 1, 1) != DIRECTORY_SEPARATOR) {
            $dir .= DIRECTORY_SEPARATOR;
        }

        $files = glob($dir . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteRecursively($file);
            } else {
                unlink($file);
            }
        }

        rmdir($dir);
        echo "Language " . $this->args[2] . " deleted successfully! \n \n";
    }

}