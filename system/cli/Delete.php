<?php

namespace Cli;

use Core\{Cache, Maintenance};

class Delete
{

    private $argv;


    public function __construct($argv)
    {
        $this->argv = $argv;
        $this->index();
    }


    public function index()
    {
        switch ($this->argv[2]) {
            case 'controller':
                $this->controller();
                break;
            case 'view':
                $this->view();
                break;
            case 'extension':
                $this->extension();
                break;
            case 'language':
                $this->language();
                break;
            case 'ip':
                $this->ip();
                break;
            case 'cache':
                $this->cache();
                break;
            default:
                echo "\e[1;31m WARNING: Command doesn't exists\e[0m\n";
                break;
        }
    }


    private function controller()
    {
        if (empty($this->argv[3])) {
            echo "\e[1;31m WARNING: no name specified!\e[0m \n";
            return;
        }

        $file_dir = getControllerPath($this->argv[3]);

        if (!is_file($file_dir)) {
            echo "\e[1;31m WARNING: the controller '" . $this->argv[3] . "' doesn't exists!\e[0m \n";

            return;
        }

        echo "Are you sure about deleting the " . $this->argv[3] . ".php controller? Y/N \n";
        $response = readline(" -> ");
        if ($response === 'Y') {
            unlink($file_dir);
            echo "Controller " . $this->argv[3] . " deleted successfully! \n";
        }
    }


    private function view()
    {
        if (empty($this->argv[3])) {
            echo "\e[1;31m WARNING: no name specified!\e[0m \n";
            return;
        }

        $file_dir = getViewPath($this->argv[3]);

        if (!is_file($file_dir)) {
            echo "\e[1;31m WARNING: the view '" . $this->argv[3] . "' doesn't exists!\e[0m \n";

            return;
        }

        echo "Are you sure about deleting the " . $this->argv[3] . " view? Y/N \n";
        $response = readline(" -> ");
        if ($response === 'Y') {
            unlink($file_dir);
            echo "View " . $this->argv[3] . " deleted successfully! \n";
        }
    }


    private function extension()
    {
        if (empty($this->argv[3])) {
            echo "\e[1;31m WARNING: no name specified!\e[0m \n";
            return;
        }

        $file_dir = getExtensionDirectory() . $this->argv[3] . '.php';

        if (!is_file($file_dir)) {
            echo "\e[1;31m WARNING: the extension '" . $this->argv[3] . "' doesn't exists!\e[0m \n";

            return;
        }

        echo "Are you sure about deleting the " . $this->argv[3] . " extension? Y/N \n";
        $response = readline(" -> ");
        if ($response === 'Y') {
            unlink($file_dir);
            echo "Extension " . $this->argv[3] . " deleted successfully! \n";
        }
    }


    private function ip()
    {
        if (empty($this->argv[3])) {
            echo "\e[1;31m WARNING: no name specified!\e[0m \n";
            return;
        }

        if (Maintenance::removeAllowedIP($this->argv[3])) {
            echo "IP " . $this->argv[3] . " removed successfully! \n";
        } else {
            echo "\e[1;31m WARNING: IP " . $this->argv[3] . " not removed!\e[0m \n";
        }
    }


    private function language()
    {
        if (empty($this->argv[3])) {
            echo "\e[1;31m WARNING: no name specified!\e[0m \n";
            return;
        }

        $language_dir = getAppDirectory() . 'languages/' . $this->argv[3];

        echo "Are you sure about deleting the " . $this->argv[3] . " language? Y/N \n";
        $response = readline(" -> ");
        if ($response === 'Y') {
            $this->deleteRecursively($language_dir);
        }
    }


    private function cache()
    {
        if (!is_dir(getCacheDirectory())) {
            echo "\e[1;31m WARNING: the cache folder doesn't exists!\e[0m \n";

            return;
        }

        if (count(glob(getCacheDirectory() . '/*')) <= 0) {
            echo "\e[1;31m WARNING: the cache folder is already empty!\e[0m \n";

            return;
        }

        if (!isset($this->argv[3])) {
            Cache::clear();
        } else {
            Cache::delete($this->argv[3]);
        }

        echo "Cache deleted successfully! \n";
    }


    private function deleteRecursively($dir)
    {
        if (!is_dir($dir)) {
            echo "\e[1;31m WARNING: the language '" . $this->argv[3] . "' doesn't exists!\e[0m \n";

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
        echo "Language " . $this->argv[3] . " deleted successfully! \n";
    }

}
