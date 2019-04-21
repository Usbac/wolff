<?php

namespace Cli;

use Utilities\Maintenance;

class Create
{
    private $route;
    private $extension;
    private $app_dir;
    private $args;
    private $routes_dir;


    public function __construct($route, $extension, $app_dir) {
        $this->route = &$route;
        $this->extension = &$extension;
        $this->app_dir = $app_dir;
        $this->routes_dir = 'Routes.php';
    }


    public function index($args) {
        $this->args = $args;
        $function = $this->args[1];

        if (method_exists($this, $function)) {
            $this->$function();
        } else {
            echo "\e[1;31m WARNING: Command doesn't exists!\e[0m \n \n";
        }
    }


    private function page() {
        if (!isset($this->args[2]) || empty($this->args[2])) {
            echo "\e[1;31m WARNING: No name specified!\e[0m \n \n";
            return;
        }
        
        $this->controller();
        $this->view();
    }


    private function controller() {
        if (!isset($this->args[2]) || empty($this->args[2])) {
            echo "\e[1;31m WARNING: No name specified!\e[0m \n \n";
            return;
        }

        $file_dir = $this->app_dir . 'controllers/' . $this->args[2] . '.php';

        if (file_exists($file_dir)) {
            echo "\e[1;31m WARNING: controller " . $this->args[2] . " already exists!\e[0m \n \n";
            return;
        }

        $file_name = "";
        $namespace = $this->createNamespace($file_dir, $file_name, 'controllers');

        $file = fopen($file_dir, 'w') or die("WARNING: Cannot create Controller file \n \n");

        $content = file_get_contents('CLI/templates/controller.txt');
        $original = array('{namespace}', '{classname}');
        $replacement = array($namespace, $file_name);
        $content = str_replace($original, $replacement, $content);

        fwrite($file, $content);
        fclose($file);

        echo "Controller " . $this->args[2] . " created successfully! \n \n";
    }


    private function view() {
        if (!isset($this->args[2]) || empty($this->args[2])) {
            echo "\e[1;31m WARNING: No name specified!\e[0m \n \n";
            return;
        }
        
        $file_dir = $this->app_dir . 'views/' . $this->args[2] . '.php';

        if (file_exists($file_dir)) {
            echo "\e[1;31m WARNING: view " . $this->args[2] . " already exists!\e[0m \n \n";
            return;
        }

        $dir = explode('/', $this->args[2]);

        if (count($dir) > 0) {
            array_pop($dir);
            $this->createDirectoryInApp($dir, 'views');
        }

        $file = fopen($file_dir, 'w') or die("WARNING: Cannot create view file \n \n");

        $content = file_get_contents('CLI/templates/view.txt');
        $content = str_replace('{title}', $this->args[2], $content);

        fwrite($file, $content);
        fclose($file);

        echo "View " . $this->args[2] . " created successfully! \n \n";
    }


    private function extension() {
        if (!isset($this->args[2]) || empty($this->args[2])) {
            echo "\e[1;31m WARNING: No name specified!\e[0m \n \n";
            return;
        }
        
        $file_dir = 'Extensions/' . $this->args[2] . '.php';

        if (file_exists($file_dir)) {
            echo "\e[1;31m WARNING: extension " . $this->args[2] . " already exists!\e[0m \n \n";
            return;
        }

        $file = fopen($file_dir, 'w') or die("WARNING: Cannot create extension file \n \n");

        $name = readline("Name -> ");
        $description = readline("Description -> ");
        $version = readline("Version -> ");
        $directory = readline("Directory -> ");
        $author = readline("Author -> ");

        $original = array('{classname}', '{name}', '{description}', '{version}', '{directory}', '{author}');
        $replacement = array($this->args[2], $name, $description, $version, $directory, $author);

        $content = file_get_contents('CLI/templates/extension.txt');
        $content = str_replace($original, $replacement, $content);

        fwrite($file, $content);
        fclose($file);

        echo "\nExtension " . $name . " created successfully! \n \n";
    }


    private function language() {
        if (!isset($this->args[2]) || empty($this->args[2])) {
            echo "\e[1;31m WARNING: No name specified!\e[0m \n \n";
            return;
        }

        $dir = $this->app_dir . 'languages/' . $this->args[2];

        if (!is_dir($dir)) {
            mkdir($dir);
            if (is_dir($dir)) {
                echo "Language " . $this->args[2] . " created successfully! \n \n";
                return;
            }
        }

        echo "\e[1;31m WARNING: Language " . $this->args[2] . " already exists!\e[0m \n \n";
    }


    private function library() {
        if (!isset($this->args[2]) || empty($this->args[2])) {
            echo "\e[1;31m WARNING: Library name is empty!\e[0m \n \n";
            return;
        }

        $file_dir = $this->app_dir . 'libraries/' . $this->args[2] . '.php';

        if (file_exists($file_dir)) {
            echo "\e[1;31m WARNING: Library " . $this->args[2] . " already exists!\e[0m \n \n";
            return;
        }

        $file_name = "";
        $namespace = $this->createNamespace($file_dir, $file_name, 'libraries');

        $file = fopen($file_dir, 'w') or die("WARNING: Cannot create library file \n \n");

        $content = file_get_contents('CLI/templates/library.txt');
        $original = array('{namespace}', '{classname}');
        $replacement = array($namespace, $file_name);
        $content = str_replace($original, $replacement, $content);

        fwrite($file, $content);
        fclose($file);

        echo "Library " . $this->args[2] . " created successfully! \n \n";
    }


    private function route() {
        $file = fopen($this->routes_dir, 'r') or die('WARNING: Cannot read Routes file');
        $content = fread($file, filesize($this->routes_dir));
        $route = $this->args[2];

        if (preg_match("/Route::add\((\s){0,}?[\'\"]" . $route . "[\'\"](\s){0,}?\,/", $content)) {
            echo "\e[1;31m WARNING: Route already exists!\e[0m \n \n";
            fclose($file);
            return;
        }
        fclose($file);

        $content = "\n \n";
        $content .= 'Route::add("' . $route . '", function() {' . "\n";
        $content .= '    $this->load->controller("' . $this->args[3] . '");' . "\n";
        $content .= '});';

        if (file_put_contents($this->routes_dir, $content, "\r\n" . FILE_APPEND | LOCK_EX)) {
            echo "Route " . $route . " created successfully! \n \n";
        }
    }


    private function block() {
        $file = fopen($this->routes_dir, 'r') or die('WARNING: Cannot read Routes file');
        $content = fread($file, filesize($this->routes_dir));
        $route = $this->args[2];

        if (preg_match("/Route::block\((\s){0,}?[\'\"]" . $route . "[\'\"](\s){0,}?\)\;/", $content)) {
            echo "\e[1;31m WARNING: Block already exists!\e[0m \n";
            fclose($file);
            return;
        }
        fclose($file);

        $content = PHP_EOL . PHP_EOL . 'Route::block("' . $route . '");';

        if (file_put_contents($this->routes_dir, $content, "\r\n" . FILE_APPEND | LOCK_EX)) {
            echo "Route " . $route . " blocked successfully! \n \n";
        }
    }


    private function redirect() {
        $file = fopen($this->routes_dir, 'r') or die('WARNING: Cannot read Routes file');
        $content = fread($file, filesize($this->routes_dir));
        $original = $this->args[2];
        $redirect = $this->args[3];
        $redirect_code = "";

        if (preg_match("/Route::redirect\((\s){0,}?[\'\"]" . $original . "[\'\"]\,(\s){0,}?[\'\"]" . $redirect . "[\'\"]/", $content)) {
            echo "\e[1;31m WARNING: Redirect already exists!\e[0m \n \n";
            fclose($file);
            return;
        }
        fclose($file);

        if (isset($this->args[4]) && is_numeric($this->args[4])) {
            $redirect_code = ", " . $this->args[4];
        }

        $content = PHP_EOL . PHP_EOL . 'Route::redirect("' . $original . '", "' . $redirect . '"' . $redirect_code . ');';

        if (file_put_contents($this->routes_dir, $content, "\r\n" . FILE_APPEND | LOCK_EX)) {
            echo "Redirect " . $original . "->" . $redirect . " created successfully! \n \n";
        }
    }


    private function ip() {
        if (!isset($this->args[2]) || empty($this->args[2])) {
            echo "\e[1;31m WARNING: No IP address specified!\e[0m \n \n";
            return;
        }

        if (Maintenance::addAllowedIP($this->args[2])) {
            echo "IP " . $this->args[2] . " added successfully! \n \n";
        } else {
            echo "\e[1;31m WARNING: IP " . $this->args[2] . " not added!\e[0m \n \n";
        }
    }


    private function createDirectoryInApp($dir, $folder) {
        $dir = $this->app_dir . $folder . '/' . implode('/', $dir);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }


    private function createNamespace($dir, &$file_name, $type) {
        $dir = explode('/', $this->args[2]);
        $file_name = array_pop($dir);

        if (count($dir) > 0) {
            $this->createDirectoryInApp($dir, $type);
        }

        $namespace = implode('\\', $dir);
        if (!empty($namespace)) {
            return "\\" . $namespace;
        }

        return $namespace;
    }

}