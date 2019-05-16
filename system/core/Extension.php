<?php

namespace Core;

use Utilities\Str;

class Extension
{

    const NAMESPACE = 'Extension\\';

    /**
     * List of extensions
     *
     * @var array
     */
    private static $extensions;


    const FILE = 'system/definitions/Extensions.php';
    const ALL = '*';


    /**
     * Load all the extensions files that matches the current route
     *
     * @param  string  $type  the type of extensions to load
     * @param  mixed  $loader  the loader class
     *
     * @return bool true if the extensions have been loaded, false otherwise
     */
    public static function load(string $type, $loader = null)
    {
        if (!self::isEnabled() || empty(self::$extensions)) {
            return false;
        }

        self::makeFolder();

        foreach (self::$extensions as $extension) {
            if ($extension['type'] !== $type || !self::matchesRoute($extension['route'])) {
                continue;
            }

            $class = self::NAMESPACE . $extension['name'];

            if (!class_exists($class)) {
                continue;
            }

            $extension = Factory::extension($extension['name']);
            $extension->load = $loader;
            $extension->session = $loader->getSession();
            $extension->upload = $loader->getUpload();
            $extension->index();
        }

        return true;
    }


    /**
     * Returns true if the extensions are enabled, false otherwise
     * @return bool true if the extensions are enabled, false otherwise
     */
    public static function isEnabled()
    {
        return WOLFF_EXTENSIONS_ON;
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
        $dirLength = count($dir) - 1;

        $url = explode('/', Str::sanitizeURL(getCurrentPage()));
        $urlLength = count($url) - 1;

        for ($i = 0; $i <= $dirLength && $i <= $urlLength; $i++) {
            if ($dir[$i] === self::ALL) {
                return true;
            }

            if ($url[$i] != $dir[$i] && !empty($dir[$i]) && !Route::isGetVariable($dir[$i])) {
                return false;
            }

            //Finish if last GET variable from url is empty
            if ($i + 1 === $dirLength && $i === $urlLength && Route::isGetVariable($dir[$i + 1])) {
                return true;
            }

            //Finish if in the end of the route
            if ($i === $dirLength && $i === $urlLength) {
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
        return file_exists(getExtensionDirectory());
    }


    /**
     * Make the extension folder directory if it doesn't exists
     */
    public static function makeFolder()
    {
        if (!self::folderExists()) {
            mkdir(getExtensionDirectory());
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
        self::$extensions[] = array(
            'name'  => $extension_name,
            'route' => $route,
            'type'  => 'after'
        );
    }


    /**
     * Add an extension of type before
     *
     * @param  string  $route  the desired route where it will work
     * @param  string  $extension_name  the extension name
     */
    public static function before(string $route, string $extension_name)
    {
        self::$extensions[] = array(
            'name'  => $extension_name,
            'route' => $route,
            'type'  => 'before'
        );
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
        $files = glob(getExtensionDirectory() . self::ALL . '.php');
        $extensions = [];

        foreach ($files as $file) {
            $filename = basename($file, '.php');
            $extension = Factory::extension($filename);

            $extensions[] = array(
                'name'        => $extension->desc['name'] ?? '',
                'description' => $extension->desc['description'] ?? '',
                'version'     => $extension->desc['version'] ?? '',
                'author'      => $extension->desc['author'] ?? '',
                'filename'    => $filename
            );
        }

        return $extensions;
    }
}
