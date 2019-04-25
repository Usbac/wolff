<?php

namespace Cli;

use Utilities\Maintenance;

class Delete
{
    private $route;
    private $app_dir;
    private $args;


    public function __construct($route, $app_dir)
    {
        $this->route   = &$route;
        $this->app_dir = $app_dir;
    }


    public function index($args)
    {
        $this->args = $args;
        $function   = $this->args[1];

        if (method_exists($this, $function)) {
            $this->$function();
        } else {
            echo "\e[1;31m WARNING: Command doesn't exists!\e[0m \n \n";
        }
    }


    private function controller()
    {
        $file_dir = $this->app_dir . 'controllers/' . $this->args[2] . '.php';

        if (!is_file($file_dir)) {
            echo "\e[1;31m WARNING: the controller '" . $this->args[2] . "' doesn't exists!\e[0m \n \n";

            return;
        }

        echo "Are you sure about deleting the " . $this->args[2] . ".php controller? Y/N \n";
        $response = readline(" -> ");
        if ($response === 'Y') {
            unlink($file_dir);
            echo "Controller " . $this->args[2] . " deleted successfully! \n \n";
        } else {
            echo "\n";
        }
    }


    private function library()
    {
        $file_dir = $this->app_dir . 'libraries/' . $this->args[2] . '.php';

        if (!is_file($file_dir)) {
            echo "\e[1;31m WARNING: the library '" . $this->args[2] . "' doesn't exists!\e[0m \n \n";

            return;
        }

        echo "Are you sure about deleting the " . $this->args[2] . ".php library? Y/N \n";
        $response = readline(" -> ");
        if ($response === 'Y') {
            unlink($file_dir);
            echo "Library " . $this->args[2] . " deleted successfully! \n \n";
        } else {
            echo "\n";
        }
    }


    private function view()
    {
        $file_dir = $this->app_dir . 'views/' . $this->args[2];

        if (!is_file($file_dir)) {
            echo "\e[1;31m WARNING: the view '" . $this->args[2] . "' doesn't exists!\e[0m \n \n";

            return;
        }

        echo "Are you sure about deleting the " . $this->args[2] . " view? Y/N \n";
        $response = readline(" -> ");
        if ($response === 'Y') {
            unlink($file_dir);
            echo "View " . $this->args[2] . " deleted successfully! \n \n";
        } else {
            echo "\n";
        }
    }


    private function extension()
    {
        $file_dir = '../extension/' . $this->args[2] . '.php';

        if (!is_file($file_dir)) {
            echo "\e[1;31m WARNING: the extension '" . $this->args[2] . "' doesn't exists!\e[0m \n \n";

            return;
        }

        echo "Are you sure about deleting the " . $this->args[2] . " extension? Y/N \n";
        $response = readline(" -> ");
        if ($response === 'Y') {
            unlink($file_dir);
            echo "Extension " . $this->args[2] . " deleted successfully! \n \n";
        } else {
            echo "\n";
        }
    }


    private function ip()
    {
        if (Maintenance::removeAllowedIP($this->args[2])) {
            echo "IP " . $this->args[2] . " removed successfully! \n \n";
        } else {
            echo "\e[1;31m WARNING: IP " . $this->args[2] . " not removed!\e[0m \n \n";
        }
    }


    private function language()
    {
        $language_dir = $this->app_dir . 'languages/' . $this->args[2];
        $this->deleteRecursively($language_dir);
    }


    private function cache()
    {
        $cache_path = '../cache';

        if (!is_dir($cache_path)) {
            echo "\e[1;31m WARNING: the cache folder doesn't exists!\e[0m \n \n";

            return;
        }

        $files = glob($cache_path . '/*');

        if (count($files) <= 0) {
            echo "\e[1;31m WARNING: the cache folder is already empty!\e[0m \n \n";

            return;
        }

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        echo "Cache deleted successfully! \n \n";
    }


    private function deleteRecursively($dir)
    {
        if (!is_dir($dir)) {
            echo "\e[1;31m WARNING: the language '" . $this->args[2] . "' doesn't exists!\e[0m \n \n";

            return;
        }

        if (substr($dir, strlen($dir) - 1, 1) != '/') {
            $dir .= '/';
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