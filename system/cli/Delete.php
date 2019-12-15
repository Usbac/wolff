<?php

namespace Cli;

use Core\{Cache, Maintenance};

class Delete
{

    const OPTIONS = [
        'controller',
        'view',
        'middleware',
        'language',
        'ip',
        'cache'
    ];

    private $argv;


    public function __construct($argv)
    {
        $this->argv = $argv;
        $this->index();
    }


    public function index()
    {
        if (!isset($this->argv[2]) || empty($this->argv[2])) {
            echo "\e[1;31m WARNING: No element specified for removing\e[0m\n";
            return;
        }

        if (in_array($this->argv[2], self::OPTIONS)) {
            $this->{$this->argv[2]}();
        } else {
            echo "\e[1;31m WARNING: Command doesn't exists\e[0m\n";
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

        $response = readline("Are you sure about deleting the " . $this->argv[3] . " view? [Y/N] \n");
        if ($response === 'Y') {
            unlink($file_dir);
            echo "View " . $this->argv[3] . " deleted successfully! \n";
        }
    }


    private function middleware()
    {
        if (empty($this->argv[3])) {
            echo "\e[1;31m WARNING: no name specified!\e[0m \n";
            return;
        }

        $file_dir = getAppDir() . CORE_CONFIG['middlewares_dir'] . '/' . $this->argv[3] . '.php';

        if (!is_file($file_dir)) {
            echo "\e[1;31m WARNING: the middleware '" . $this->argv[3] . "' doesn't exists!\e[0m \n";

            return;
        }

        $response = readline("Are you sure about deleting the " . $this->argv[3] . " middleware? [Y/N] \n");
        if ($response === 'Y') {
            unlink($file_dir);
            echo "Middleware " . $this->argv[3] . " deleted successfully! \n";
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

        $language_dir = getAppDir() . 'languages/' . $this->argv[3];

        if (!is_dir($language_dir)) {
            echo "\e[1;31m WARNING: the language '" . $this->argv[3] . "' doesn't exists!\e[0m \n";
            return;
        }

        $response = readline("Are you sure about deleting the " . $this->argv[3] . " language? [Y/N] \n");
        if ($response === 'Y') {
            $this->deleteRecursively($language_dir);
        }
    }


    private function cache()
    {
        if (!is_dir(getCacheDir())) {
            echo "\e[1;31m WARNING: the cache folder doesn't exists!\e[0m \n";

            return;
        }

        if (count(glob(getCacheDir() . '/*')) <= 0) {
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
