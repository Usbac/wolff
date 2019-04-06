<?php

namespace Cli;

use System as Sys;

class Create {
    private $route;
    private $extension;
    private $app_dir;
    private $args;
    private $routes_dir;


    public function __construct($route, $extension, $app_dir)  {
        $this->route = &$route;
        $this->extension = &$extension;
        $this->app_dir = $app_dir;
        $this->routes_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'Routes.php';
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
        $this->view();
    }


    private function controller() {
        $file_dir = $this->app_dir . 'controller' . DIRECTORY_SEPARATOR . $this->args[2] . '.php';

        if (file_exists($file_dir)) {
            echo "WARNING: controller " . $this->args[2] . " already exists \n \n";
            return;
        }
        
        $file_name = "";
        $namespace = $this->createNamespace($dir, $file_name, 'controller');

        $file = fopen($file_dir, 'w') or die("WARNING: Cannot create Controller file \n \n");

        $content = file_get_contents('templates/controller.txt');
        $original = array('{namespace}', '{classname}');
        $replacement = array($namespace, $file_name);
        $content = str_replace($original, $replacement, $content);

        fwrite($file, $content);
        fclose($file);
        
        echo "Controller " . $this->args[2] . " created successfully! \n \n";
    }
    

    private function model() {
        $file_dir = $this->app_dir . 'model' . DIRECTORY_SEPARATOR . $this->args[2] . '.php';

        if (file_exists($file_dir)) {
            echo "WARNING: model " . $this->args[2] . " already exists \n \n";
            return;
        }

        $file_name = "";
        $namespace = $this->createNamespace($dir, $file_name, 'model');

        $file = fopen($file_dir, 'w') or die("WARNING: Cannot create model file \n \n");

        $content = file_get_contents('templates/model.txt');
        $original = array('{namespace}', '{classname}');
        $replacement = array($namespace, $file_name);
        $content = str_replace($original, $replacement, $content);

        fwrite($file, $content);
        fclose($file);

        echo "Model " . $this->args[2] . " created successfully! \n \n";
    }
    

    private function view() {
        $file_dir = $this->app_dir . 'view' . DIRECTORY_SEPARATOR . $this->args[2] . '.php';

        if (file_exists($file_dir)) {
            echo "WARNING: view " . $this->args[2] . " already exists \n \n";
            return;
        }

        $dir = explode('/', $this->args[2]);
        $file_name = array_pop($dir);

        if (count($dir) > 0) {
            $this->createDirectoryInApp($dir, 'view');
        }

        $file = fopen($file_dir, 'w') or die("WARNING: Cannot create view file \n \n");
        
        $content = file_get_contents('templates/view.txt');
        $content = str_replace('{title}', $this->args[2], $content);
        
        fwrite($file, $content);
        fclose($file);

        echo "View " . $this->args[2] . " created successfully! \n \n";
    }


    private function extension() {
        $file_dir = '..' . DIRECTORY_SEPARATOR . 'extension' . DIRECTORY_SEPARATOR . $this->args[2] . '.php';

        if (file_exists($file_dir)) {
            echo "WARNING: extension " . $this->args[2] . " already exists \n \n";
            return;
        }

        $file = fopen($file_dir, 'w') or die("WARNING: Cannot create extension file \n \n");

        $name = readline("Name -> ");
        $description = readline("Description -> ");
        $version = readline("Version -> ");
        $author = readline("Author -> ");

        $original = array('{classname}', '{name}', '{description}', '{version}', '{author}');
        $replacement = array($this->args[2], $name, $description, $version, $author);
        
        $content = file_get_contents('templates/extension.txt');
        $content = str_replace($original, $replacement, $content);
        
        fwrite($file, $content);
        fclose($file);

        echo "\nExtension " . $name . " created successfully! \n \n";
    }


    private function language() {
        $dir = $this->app_dir . 'language' . DIRECTORY_SEPARATOR . $this->args[2];

        if (!is_dir($dir)) {
            mkdir($dir);
            if (is_dir($dir)) {
                echo "Language " . $this->args[2] . " created successfully! \n \n";
                return;
            }
        }

        echo "WARNING: Language " . $this->args[2] . " already exists \n \n";
    }


    private function library() {
        if (empty($this->args[2])) {
            echo "WARNING: Library name is empty \n \n";
            return;
        }

        $file_dir = $this->app_dir . 'library' . DIRECTORY_SEPARATOR . $this->args[2] . '.php';

        if (file_exists($file_dir)) {
            echo "WARNING: Library " . $this->args[2] . " already exists \n \n";
            return;
        }

        $file_name = "";
        $namespace = $this->createNamespace($dir, $file_name, 'library');

        $file = fopen($file_dir, 'w') or die("WARNING: Cannot create library file \n \n");

        $content = file_get_contents('templates/library.txt');
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
            echo "WARNING: Route already exists \n \n";
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
            echo "WARNING: Block already exists \n";
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
        $original =  $this->args[2];
        $redirect = $this->args[3];
        $redirect_code = "";

        if (preg_match("/Route::redirect\((\s){0,}?[\'\"]" . $original . "[\'\"]\,(\s){0,}?[\'\"]" . $redirect . "[\'\"]/", $content)) {
            echo "WARNING: Redirect already exists \n \n";
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


    private function createDirectoryInApp($dir, $folder) {
        $dir = $this->app_dir . $folder . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $dir);

        if (!is_dir($dir)) {
            mkdir($dir);
        }
    }


    private function createNamespace(&$dir, &$file_name, $type) {
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