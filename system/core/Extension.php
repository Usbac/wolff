<?php

namespace Core;

use Utilities\Str;

class Extension
{

    /**
     * List of extensions
     *
     * @var array
     */
    private static $extensions;

    const NAMESPACE = 'Extension\\';
    const FILE = 'system/definitions/Extensions.php';
    const BEFORE = 'before';
    const AFTER = 'after';
    const ALL = '*';


    /**
     * Load all the extensions files that matches the current route
     *
     * @param  string  $type  the type of extensions to load
     *
     * @return bool true if the extensions have been loaded, false otherwise
     */
    private static function load(string $type)
    {
        if (!self::isEnabled() || empty(self::$extensions)) {
            return false;
        }

        self::mkdir();

        foreach (self::$extensions as $ext) {
            if ($ext['type'] !== $type || !self::matchesRoute($ext['route'])) {
                continue;
            }

            $class = self::NAMESPACE . $ext['name'];

            if (!class_exists($class)) {
                continue;
            }

            $extension = Factory::extension($ext['name']);

            if (method_exists($extension, 'index')) {
                $extension->index();
            } else {
                Log::error("The extension '" . $ext['name'] . "' doesn't have an index method");
            }
        }

        return true;
    }


    /**
     * Load the extensions files of type before that matches the current route
     *
     * @return bool true if the extensions have been loaded, false otherwise
     */
    public static function loadBefore()
    {
        return self::load(Extension::BEFORE);
    }


    /**
     * Load the extensions files of type after that matches the current route
     *
     * @return bool true if the extensions have been loaded, false otherwise
     */
    public static function loadAfter()
    {
        return self::load(Extension::AFTER);
    }


    /**
     * Returns true if the extensions are enabled, false otherwise
     * @return bool true if the extensions are enabled, false otherwise
     */
    public static function isEnabled()
    {
        return CONFIG['extensions_on'];
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

            if ($url[$i] != $dir[$i] && !empty($dir[$i]) && !Route::isGetVariable($dir[$i])) {
                return false;
            }

            //Finish if last GET variable from url is empty
            if ($i + 1 === $dir_length && $i === $url_length && Route::isGetVariable($dir[$i + 1])) {
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
     * Returns true if the extension folder exists, false otherwise
     * @return bool true if the extension folder exists, false otherwise
     */
    public static function folderExists()
    {
        return file_exists(CORE_CONFIG['extensions_folder']);
    }


    /**
     * Make the extension folder directory if it doesn't exists
     */
    public static function mkdir()
    {
        if (!self::folderExists()) {
            mkdir(CORE_CONFIG['extensions_folder']);
        }
    }


    /**
     * Add an extension of type after
     *
     * @param  string  $route  the desired route where it will work
     * @param  string  $extension_name  the extension name
     */
    public static function after(string $route, string $extension_name)
    {
        self::$extensions[] = [
            'name'  => $extension_name,
            'route' => $route,
            'type'  => self::AFTER
        ];
    }


    /**
     * Add an extension of type before
     *
     * @param  string  $route  the desired route where it will work
     * @param  string  $extension_name  the extension name
     */
    public static function before(string $route, string $extension_name)
    {
        self::$extensions[] = [
            'name'  => $extension_name,
            'route' => $route,
            'type'  => self::BEFORE
        ];
    }


    /**
     * Get the extensions list
     *
     * @param  string the extensions filename
     *
     * @return array the extensions list if the name is empty or the specified extension otherwise
     */
    public static function get(string $name = null)
    {
        //Specified extension
        if (!isset($name)) {
            $class = self::NAMESPACE . $name;

            if (class_exists($class)) {
                return Factory::extension($name)->desc;
            }

            return false;
        }

        //All the extensions
        $files = glob(CORE_CONFIG['extensions_folder'] . self::ALL . '.php');
        $extensions = [];

        foreach ($files as $file) {
            $filename = basename($file, '.php');
            $extension = Factory::extension($filename);

            $extensions[] = [
                'name'        => $extension->desc['name'] ?? '',
                'description' => $extension->desc['description'] ?? '',
                'version'     => $extension->desc['version'] ?? '',
                'author'      => $extension->desc['author'] ?? '',
                'filename'    => $filename
            ];
        }

        return $extensions;
    }
}
