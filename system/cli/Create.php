<?php

namespace Cli;

use Core\{Middleware, Maintenance};
use Utilities\Str;

class Create
{

    const TEMPLATE_PATH = __DIR__ . '/templates/';
    const ROUTES_PATH = 'system/definitions/Routes.php';
    const MIDDLEWARES_FILE = 'system/definitions/Middlewares.php';
    const OPTIONS = [
        'page',
        'controller',
        'view',
        'middleware',
        'language',
        'route',
        'block',
        'redirect',
        'ip'
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
            echo "\e[1;31m WARNING: No element specified for creation\e[0m\n";
            return;
        }

        if (in_array($this->argv[2], self::OPTIONS)) {
            $this->{$this->argv[2]}();
        } else {
            echo "\e[1;31m WARNING: Command doesn't exists\e[0m\n";
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

        $file_dir = getControllerPath($this->argv[3]);

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

        $file_dir = getViewPath($this->argv[3] . '.php');

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


    private function middleware()
    {
        if (!isset($this->argv[3]) || empty($this->argv[3])) {
            echo "\e[1;31m WARNING: No name specified!\e[0m \n";

            return;
        }

        $file_dir = getAppDir() . CORE_CONFIG['middlewares_dir'] . '/' . $this->argv[3] . '.php';
        Middleware::mkdir();

        if (file_exists($file_dir)) {
            echo "\e[1;31m WARNING: middleware " . $this->argv[3] . " already exists!\e[0m \n";

            return;
        }

        $file = fopen($file_dir, 'w') or die("WARNING: Cannot create middleware file \n");

        $name = readline("Name -> ");
        $description = readline("Description -> ");
        $directory = readline("Directory -> ");
        $type = readline("(B)efore / (A)fter? -> ");

        $values = [
            'classname'   => $this->argv[3],
            'name'        => $name,
            'description' => $description,
        ];

        $content = file_get_contents(self::TEMPLATE_PATH . 'middleware.txt');
        $content = Str::interpolate($content, $values);

        fwrite($file, $content);
        fclose($file);

        //Add middleware route to Middleware.php
        $this->middlewareFile($type, $directory, $this->argv[3]);

        echo "\nMiddleware " . $name . " created successfully! \n";
    }


    private function middlewareFile($type, $directory, $name)
    {
        $type = $type === 'B' ? 'before' : 'after';
        $route = PHP_EOL . "Middleware::" . $type . "('" . $directory . "', '" . $name . "');";
        file_put_contents(self::MIDDLEWARES_FILE, $route, FILE_APPEND | LOCK_EX);
    }


    private function language()
    {
        if (!isset($this->argv[3]) || empty($this->argv[3])) {
            echo "\e[1;31m WARNING: No name specified!\e[0m \n";

            return;
        }

        $dir = getAppDir() . 'languages/' . $this->argv[3];

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
        $file = fopen(self::ROUTES_PATH, 'r') or die('WARNING: Cannot read Routes file');
        $content = fread($file, filesize(self::ROUTES_PATH));
        $route = $this->argv[3];
        $controller = $this->argv[4];

        if (preg_match("/Route::get\((\s){0,}?[\'\"]" . $route . "[\'\"](\s){0,}?\,/", $content)) {
            echo "\e[1;31m WARNING: Route already exists!\e[0m \n";
            fclose($file);

            return;
        }
        fclose($file);

        $content = "\n";
        $content .= "Route::get('" . $route . "', function() { \n";
        $content .= "    Controller::call('" . $controller . "'); \n";
        $content .= "});";

        if (file_put_contents(self::ROUTES_PATH, $content, "\r\n" . FILE_APPEND | LOCK_EX)) {
            echo "Route " . $route . " created successfully! \n";
        }
    }


    private function block()
    {
        $file = fopen(self::ROUTES_PATH, 'r') or die('WARNING: Cannot read Routes file');
        $content = fread($file, filesize(self::ROUTES_PATH));
        $route = $this->argv[3];

        if (preg_match("/Route::block\((\s){0,}?[\'\"]" . $route . "[\'\"](\s){0,}?\)\;/", $content)) {
            echo "\e[1;31m WARNING: Block already exists!\e[0m \n";
            fclose($file);

            return;
        }
        fclose($file);

        $content = PHP_EOL . PHP_EOL . 'Route::block("' . $route . '");';

        if (file_put_contents(self::ROUTES_PATH, $content, "\r\n" . FILE_APPEND | LOCK_EX)) {
            echo "Route " . $route . " blocked successfully! \n";
        }
    }


    private function redirect()
    {
        $file = fopen(self::ROUTES_PATH, 'r') or die('WARNING: Cannot read Routes file');
        $content = fread($file, filesize(self::ROUTES_PATH));
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

        if (file_put_contents(self::ROUTES_PATH, $content, "\r\n" . FILE_APPEND | LOCK_EX)) {
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
        $dir = getAppDir() . $folder . '/' . implode('/', $dir);

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
