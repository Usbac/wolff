<?php

namespace Cli;

use Core\{Extension, Maintenance};
use Utilities\Str;

class Create
{

    const TEMPLATE_PATH = 'system/cli/templates/';

    private $routes_dir;
    private $argv;


    public function __construct($argv)
    {
        $this->routes_dir = 'System/definitions/Routes.php';
        $this->argv = $argv;
        $this->index();
    }


    public function index()
    {
        switch ($this->argv[2]) {
            case 'page':
                $this->page();
                break;
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
            case 'route':
                $this->route();
                break;
            case 'block':
                $this->block();
                break;
            case 'redirect':
                $this->redirect();
                break;
            case 'ip':
                $this->ip();
                break;
            default:
                echo "\e[1;31m WARNING: Command doesn't exists\e[0m\n";
                break;
        }
    }


    private function page()
    {
        if (!isset($this->argv[3]) || empty($this->argv[3])) {
            echo "\e[1;31m WARNING: No name specified!\e[0m \n";

            return;
        }

        $this->controller();
        $this->view();
    }


    private function controller()
    {
        if (!isset($this->argv[3]) || empty($this->argv[3])) {
            echo "\e[1;31m WARNING: No name specified!\e[0m \n";

            return;
        }

        $file_dir = getAppDirectory() . 'controllers/' . $this->argv[3] . '.php';

        if (file_exists($file_dir)) {
            echo "\e[1;31m WARNING: controller " . $this->argv[3] . " already exists!\e[0m \n";

            return;
        }

        $file_name = "";
        $namespace = $this->createNamespace($file_dir, $file_name, 'controllers');

        $file = fopen($file_dir, 'w') or die("WARNING: Cannot create Controller file \n");

        $values = [
            'namespace' => $namespace,
            'classname' => $file_name
        ];

        $content = file_get_contents(self::TEMPLATE_PATH . 'controller.txt');
        $content = Str::interpolate($content, $values);

        fwrite($file, $content);
        fclose($file);

        echo "Controller " . $this->argv[3] . " created successfully! \n";
    }


    private function view()
    {
        if (!isset($this->argv[3]) || empty($this->argv[3])) {
            echo "\e[1;31m WARNING: No name specified!\e[0m \n";

            return;
        }

        $file_dir = getAppDirectory() . 'views/' . $this->argv[3] . '.php';

        if (file_exists($file_dir)) {
            echo "\e[1;31m WARNING: view " . $this->argv[3] . " already exists!\e[0m \n";

            return;
        }

        $dir = explode('/', $this->argv[3]);

        if (count($dir) > 0) {
            array_pop($dir);
            $this->createDirectoryInApp($dir, 'views');
        }

        $file = fopen($file_dir, 'w') or die("WARNING: Cannot create view file \n");

        $content = file_get_contents(self::TEMPLATE_PATH . 'view.txt');
        $content = Str::interpolate($content, ['title' => $this->argv[3]]);

        fwrite($file, $content);
        fclose($file);

        echo "View " . $this->argv[3] . " created successfully! \n";
    }


    private function extension()
    {
        if (!isset($this->argv[3]) || empty($this->argv[3])) {
            echo "\e[1;31m WARNING: No name specified!\e[0m \n";

            return;
        }

        $file_dir = getExtensionDirectory() . $this->argv[3] . '.php';
        Extension::mkdir();

        if (file_exists($file_dir)) {
            echo "\e[1;31m WARNING: extension " . $this->argv[3] . " already exists!\e[0m \n";

            return;
        }

        $file = fopen($file_dir, 'w') or die("WARNING: Cannot create extension file \n");

        $name = readline("Name -> ");
        $description = readline("Description -> ");
        $directory = readline("Directory -> ");
        $type = readline("(B)efore / (A)fter? -> ");
        $version = readline("Version -> ");
        $author = readline("Author -> ");

        $values = [
            'classname'   => $this->argv[3],
            'name'        => $name,
            'description' => $description,
            'version'     => $version,
            'author'      => $author,
        ];

        $content = file_get_contents(self::TEMPLATE_PATH . 'extension.txt');
        $content = Str::interpolate($content, $values);

        fwrite($file, $content);
        fclose($file);

        //Add extension route to Extensions.php
        $this->extensionFile($type, $directory, $this->argv[3]);

        echo "\nExtension " . $name . " created successfully! \n";
    }


    private function extensionFile($type, $directory, $name)
    {
        $type = $type === 'B'? 'before':'after';
        $route = PHP_EOL . "Extension::" . $type . "('" . $directory . "', '" . $name . "');";
        file_put_contents('system/definitions/Extensions.php', $route, FILE_APPEND | LOCK_EX);
    }


    private function language()
    {
        if (!isset($this->argv[3]) || empty($this->argv[3])) {
            echo "\e[1;31m WARNING: No name specified!\e[0m \n";

            return;
        }

        $dir = getAppDirectory() . 'languages/' . $this->argv[3];

        if (!is_dir($dir)) {
            mkdir($dir);
            if (is_dir($dir)) {
                echo "Language " . $this->argv[3] . " created successfully! \n";

                return;
            }
        }

        echo "\e[1;31m WARNING: Language " . $this->argv[3] . " already exists!\e[0m \n";
    }


    private function route()
    {
        $file = fopen($this->routes_dir, 'r') or die('WARNING: Cannot read Routes file');
        $content = fread($file, filesize($this->routes_dir));
        $route = $this->argv[3];
        $controller = $this->argv[4];

        if (preg_match("/Route::add\((\s){0,}?[\'\"]" . $route . "[\'\"](\s){0,}?\,/", $content)) {
            echo "\e[1;31m WARNING: Route already exists!\e[0m \n";
            fclose($file);

            return;
        }
        fclose($file);

        $content = "\n";
        $content .= 'Route::add("' . $route . '", function() {' . "\n";
        $content .= '    $this->load->controller("' . $controller . '");' . "\n";
        $content .= '});';

        if (file_put_contents($this->routes_dir, $content, "\r\n" . FILE_APPEND | LOCK_EX)) {
            echo "Route " . $route . " created successfully! \n";
        }
    }


    private function block()
    {
        $file = fopen($this->routes_dir, 'r') or die('WARNING: Cannot read Routes file');
        $content = fread($file, filesize($this->routes_dir));
        $route = $this->argv[3];

        if (preg_match("/Route::block\((\s){0,}?[\'\"]" . $route . "[\'\"](\s){0,}?\)\;/", $content)) {
            echo "\e[1;31m WARNING: Block already exists!\e[0m \n";
            fclose($file);

            return;
        }
        fclose($file);

        $content = PHP_EOL . PHP_EOL . 'Route::block("' . $route . '");';

        if (file_put_contents($this->routes_dir, $content, "\r\n" . FILE_APPEND | LOCK_EX)) {
            echo "Route " . $route . " blocked successfully! \n";
        }
    }


    private function redirect()
    {
        $file = fopen($this->routes_dir, 'r') or die('WARNING: Cannot read Routes file');
        $content = fread($file, filesize($this->routes_dir));
        $original = $this->argv[3];
        $redirect = $this->argv[4];
        $redirect_code = "";

        if (preg_match("/Route::redirect\((\s){0,}?[\'\"]" . $original . "[\'\"]\,(\s){0,}?[\'\"]" . $redirect . "[\'\"]/",
            $content)) {
            echo "\e[1;31m WARNING: Redirect already exists!\e[0m \n";
            fclose($file);

            return;
        }
        fclose($file);

        if (isset($this->argv[4]) && is_numeric($this->argv[4])) {
            $redirect_code = ", " . $this->argv[4];
        }

        $content = PHP_EOL . PHP_EOL . 'Route::redirect("' . $original . '", "' . $redirect . '"' . $redirect_code . ');';

        if (file_put_contents($this->routes_dir, $content, "\r\n" . FILE_APPEND | LOCK_EX)) {
            echo "Redirect " . $original . "->" . $redirect . " created successfully! \n";
        }
    }


    private function ip()
    {
        if (!isset($this->argv[3]) || empty($this->argv[3])) {
            echo "\e[1;31m WARNING: No IP address specified!\e[0m \n";

            return;
        }

        if (Maintenance::addAllowedIP($this->argv[3])) {
            echo "IP " . $this->argv[3] . " added successfully! \n";
        } else {
            echo "\e[1;31m WARNING: IP " . $this->argv[3] . " not added!\e[0m \n";
        }
    }


    private function createDirectoryInApp($dir, $folder)
    {
        $dir = getAppDirectory() . $folder . '/' . implode('/', $dir);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }


    private function createNamespace($dir, &$file_name, $type)
    {
        $dir = explode('/', $this->argv[3]);
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
