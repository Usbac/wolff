<?php

namespace Cli;

use Core\{Cache, Middleware, Maintenance, Route};
use Utilities\Str;

class Lister
{

    const MIDDLEWARES_NONE = "\n Middlewares: none\n";
    const ROUTES_NONE = "\n Routes: none\n";
    const BLOCKED_NONE = "\n Blocked: none\n";
    const REDIRECTS_NONE = "\n Redirections: none\n";
    const IP_NONE = "\n Allowed IPs: none\n";
    CONST OPTIONS = [
        'middlewares',
        'views',
        'controllers',
        'languages',
        'public',
        'cache',
        'routes',
        'redirects',
        'blocked',
        'config',
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
            echo "\e[1;31m WARNING: No element specified for listing\e[0m\n";
            return;
        }

        if (in_array($this->argv[2], self::OPTIONS)) {
            $this->{$this->argv[2]}();
        } else {
            echo "\e[1;31m WARNING: Command doesn't exists\e[0m\n";
        }

    }


    private function middlewares()
    {
        $middlewares = Middleware::get();

        if (empty($middlewares)) {
            echo self::MIDDLEWARES_NONE;

            return;
        }

        foreach ($middlewares as $middleware) {
            echo "\n ->\e[32m " . $middleware['name'] . "\e[0m";
            echo "\n Description: " . $middleware['description'];
            echo "\n Filename: " . $middleware['filename'];
            echo "\n";
        }

        echo "\n";
    }


    private function views()
    {
        $views = $this->listViewFiles(getAppDir() . CORE_CONFIG['views_dir']);

        foreach ($views as $view) {
            echo "\n" . $view;
        }
        echo "\n";
    }


    private function controllers()
    {
        $controllers = $this->listPhpFiles(getAppDir() . 'controllers');

        foreach ($controllers as $controller) {
            echo "\n" . $controller;
        }
        echo "\n";
    }


    private function languages()
    {
        $languages = glob(getAppDir() . CORE_CONFIG['languages_dir'] . '/*', GLOB_ONLYDIR);

        foreach ($languages as $language) {
            echo "\n" . substr($language, strrpos($language, '/') + 1);
        }
        echo "\n";
    }


    private function cache()
    {
        $files = $this->listAnyFiles(getCacheDir());

        foreach ($files as $file) {
            echo "\n " . Str::after($file, getCacheDir());
        }
        echo "\n";
    }


    private function public()
    {
        $files = $this->listAnyFiles(getPublic());

        foreach ($files as $file) {
            echo "\n " . Str::after($file, getPublic());
        }
        echo "\n";
    }


    private function listViewFiles($dir, $folder = '', &$result = [])
    {
        $folder = substr($dir, strrpos($dir, '/') + 1);
        $files = scandir($dir);

        foreach ($files as $value) {
            $path = realpath($dir . '/' . $value);

            if (!is_dir($path) && in_array(pathinfo($path)['extension'], ['php', 'html', 'phtml'])) {
                $file_path = substr($path, strpos($path, $folder) + strlen($folder) + 1);
                $result[]  = $file_path;
            } else {
                if ($value != "." && $value != "..") {
                    $this->listFiles($path, $result);
                }
            }
        }

        return $result;
    }


    private function listPhpFiles($dir, $folder = '', &$result = [])
    {
        $folder = substr($dir, strrpos($dir, '/') + 1);
        $files = scandir($dir);

        foreach ($files as $value) {
            $path = realpath($dir . '/' . $value);

            if (!is_dir($path) && pathinfo($path)['extension'] === 'php') {
                $file_path = substr($path, strpos($path, $folder) + strlen($folder) + 1);
                $result[]  = $file_path;
            } else {
                if ($value != "." && $value != "..") {
                    $this->listPhpFiles($path, $folder, $result);
                }
            }
        }

        return $result;
    }


    private function listAnyFiles($dir, &$result = [])
    {
        $files = scandir($dir);

        foreach ($files as $value) {
            $path = realpath($dir . '/' . $value);

            if (!is_dir($path)) {
                $file_path = $path;
                $result[]  = $file_path;
            } else {
                if ($value != "." && $value != "..") {
                    $this->listAnyFiles($path, $result);
                }
            }
        }

        return $result;
    }


    private function routes()
    {
        $routes = Route::getRoutes();

        if (count($routes) <= 0) {
            echo self::ROUTES_NONE;

            return;
        } else {
            foreach ($routes as $key => $value) {
                echo "\n " . $key;
            }
        }
        echo "\n";
    }


    private function blocked()
    {
        $blocked = Route::getBlocked();

        if (count($blocked) <= 0) {
            echo self::BLOCKED_NONE;

            return;
        } else {
            foreach ($blocked as $key => $value) {
                echo "\n " . $key;
            }
        }
        echo "\n";
    }


    private function redirects()
    {
        $redirects = Route::getRedirects();

        if (count($redirects) <= 0) {
            echo self::REDIRECTS_NONE;

            return;
        } else {
            foreach ($redirects as $key => $value) {
                echo "\n " . $redirects[$key]['origin'] . " -> " . $redirects[$key]['destiny'] . " | " . $redirects[$key]['code'];
            }
        }
        echo "\n";
    }


    private function ip()
    {
        $ips = Maintenance::getAllowedIPs();

        if ($ips === false || count($ips) <= 0) {
            echo self::IP_NONE;

            return;
        }

        foreach ($ips as $ip) {
            echo "\n " . $ip;
        }
        echo "\n";
    }


    private function config()
    {
        echo "\n ->\e[32m SERVER \e[0m";
        echo "\n DBMS: " . getDBMS();
        echo "\n Server: " . getServer();
        echo "\n Database: " . getDB();
        echo "\n User: " . getDbUser();
        echo "\n Password: " . getDbPass();
        echo "\n";
        echo "\n ->\e[32m DIRECTORIES \e[0m";
        echo "\n Project folder: " . getDir();
        echo "\n App folder: " . getAppDir();
        echo "\n Public folder: " . getPublic();
        echo "\n Cache folder: " . getCacheDir();
        echo "\n";
        echo "\n ->\e[32m GENERAL \e[0m";
        echo "\n Wolff version: " . wolffVersion();
        echo "\n Page title: " . getPageTitle();
        echo "\n Main page: " . getMainPage();
        echo "\n Language: " . getLanguage();
        echo "\n";
        echo "\n ->\e[32m EXTRA \e[0m";
        echo "\n Cache enabled: " . (Cache::isEnabled() ? "yes" : "no");
        echo "\n Middleware enabled: " . (Middleware::isEnabled() ? "yes" : "no");
        echo "\n Maintenance mode enabled: " . (Maintenance::isEnabled() ? "yes" : "no");
        echo "\n";
    }

}
