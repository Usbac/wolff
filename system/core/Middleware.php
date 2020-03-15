<?php

namespace Core;

use Utilities\Str;

class Middleware
{

    const NAMESPACE = 'Middleware\\';
    const FILE = 'system/definitions/Middlewares.php';
    const FOLDER = CONFIG['app_dir'] . '/' . CORE_CONFIG['middlewares_dir'];
    const FILE_PATH = '{app}/' . CORE_CONFIG['middlewares_dir'] . '/{dir}.php';
    const BEFORE = 'before';
    const AFTER = 'after';
    const ALL = '*';

    /**
     * List of middlewares
     *
     * @var array
     */
    private static $middlewares;


    /**
     * Load all the middlewares files that matches the current route
     *
     * @param  string  $type  the type of middlewares to load
     *
     * @return bool true if the middlewares have been loaded, false otherwise
     */
    private static function load(string $type)
    {
        if (!self::isEnabled() || empty(self::$middlewares)) {
            return false;
        }

        self::mkdir();

        foreach (self::$middlewares as $ext) {
            if ($ext['type'] !== $type || !self::matchesRoute($ext['route']) ||
                !class_exists(self::NAMESPACE . $ext['name'])) {
                continue;
            }

            $middleware = Factory::middleware($ext['name']);

            if (method_exists($middleware, 'index')) {
                $middleware->index();
            } else {
                Log::error("The middleware '" . $ext['name'] . "' doesn't have an index method");
            }
        }

        return true;
    }


    /**
     * Load the middlewares files of type before that matches the current route
     *
     * @return bool true if the middlewares have been loaded, false otherwise
     */
    public static function loadBefore()
    {
        return self::load(self::BEFORE);
    }


    /**
     * Load the middlewares files of type after that matches the current route
     *
     * @return bool true if the middlewares have been loaded, false otherwise
     */
    public static function loadAfter()
    {
        return self::load(self::AFTER);
    }


    /**
     * Returns true if the middlewares are enabled, false otherwise
     * @return bool true if the middlewares are enabled, false otherwise
     */
    public static function isEnabled()
    {
        return CONFIG['middlewares_on'];
    }


    /**
     * Returns true if the directory matches the current url, false otherwise
     *
     * @param  string  $dir  the directory
     *
     * @return bool true if the directory matches the current url, false otherwise
     */
    private static function matchesRoute(string $dir)
    {
        if (empty($dir)) {
            return false;
        }

        $dir = explode('/', Str::sanitizeURL($dir));
        $dir_length = count($dir) - 1;

        $url = explode('/', Str::sanitizeURL(getCurrentPage()));
        $url_length = count($url) - 1;

        for ($i = 0; $i <= $dir_length && $i <= $url_length; $i++) {
            if ($dir[$i] === self::ALL) {
                return true;
            }

            if ($url[$i] != $dir[$i] && !empty($dir[$i]) && !Route::isGetVar($dir[$i])) {
                return false;
            }

            //Finish if last GET variable from url is empty
            if ($i + 1 === $dir_length && $i === $url_length && Route::isGetVar($dir[$i + 1])) {
                return true;
            }

            //Finish if in the end of the route
            if ($i === $dir_length && $i === $url_length) {
                return true;
            }
        }

        return false;
    }


    /**
     * Returns true if the middleware folder exists, false otherwise
     * @return bool true if the middleware folder exists, false otherwise
     */
    private static function folderExists()
    {
        return file_exists(self::FOLDER);
    }


    /**
     * Make the middleware folder directory if it doesn't exists
     */
    private static function mkdir()
    {
        if (!self::folderExists()) {
            mkdir(self::FOLDER);
        }
    }


    /**
     * Add an middleware of type after
     *
     * @param  string  $route  the desired route where it will work
     * @param  string  $middleware_name  the middleware name
     */
    public static function after(string $route, string $middleware_name)
    {
        self::$middlewares[] = [
            'name'  => $middleware_name,
            'route' => $route,
            'type'  => self::AFTER
        ];
    }


    /**
     * Add an middleware of type before
     *
     * @param  string  $route  the desired route where it will work
     * @param  string  $middleware_name  the middleware name
     */
    public static function before(string $route, string $middleware_name)
    {
        self::$middlewares[] = [
            'name'  => $middleware_name,
            'route' => $route,
            'type'  => self::BEFORE
        ];
    }


    /**
     * Get the middlewares list
     *
     * @param  string the middlewares filename
     *
     * @return array the middlewares list if the name is empty or the specified middleware otherwise
     */
    public static function get(string $name = null)
    {
        //Specified middleware
        if (isset($name)) {
            $class = self::NAMESPACE . $name;

            if (class_exists($class)) {
                return Factory::middleware($name)->desc;
            }

            return false;
        }

        //All the middlewares
        $files = glob(Str::interpolate(self::FILE_PATH, [
            'app' => CONFIG['app_dir'],
            'dir' => self::ALL,
        ]));

        $middlewares = [];

        foreach ($files as $file) {
            $filename = basename($file, '.php');
            $middleware = Factory::middleware($filename);

            $middlewares[] = [
                'name'        => $middleware->desc['name'] ?? '',
                'description' => $middleware->desc['description'] ?? '',
                'filename'    => $filename
            ];
        }

        return $middlewares;
    }
}
