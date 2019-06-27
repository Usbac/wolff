<?php

namespace Core;

use Utilities\Str;
use PDO, PDOException;

class Factory
{

    /**
     * Loader.
     *
     * @var Core\Loader
     */
    private static $loader;

    /**
     * Template manager.
     *
     * @var Core\Template
     */
    private static $template;

    /**
     * Session manager.
     *
     * @var Core\Session
     */
    private static $session;

    /**
     * Array of utilities.
     *
     * @var array
     */
    private static $utilities;

    const NAMESPACE_CONTROLLER = 'Controller\\';
    const NAMESPACE_EXTENSION = 'Extension\\';
    const NAMESPACE_UTILITY = 'Utilities\\';
    const DSN = '{dbms}:host={server}; dbname={db}';

    /**
     * Returns a PDO connection or false in case of errors
     *
     * @param  array  $options  the connection options
     *
     * @return PDO|bool a PDO connection or false in case of errors
     */
    public static function connection(array $options)
    {
        if (empty($options)) {
            return false;
        }

        try {
            $dsn = Str::interpolate(self::DSN, [
                'dbms'   => getDBMS(),
                'server' => getServer(),
                'db'     => getDB(),
            ]);

            $connection = new PDO($dsn, getDbUser(), getDbPass(), $options);
        } catch (PDOException $e) {
            Log::critical($e->getMessage());

            return false;
        }

        return $connection;
    }


    /**
     * Returns a controller initialized or false if it doesn't exists
     *
     * @param  string  $dir  the controller directory
     *
     * @return object|bool a controller initialized or false if it doesn't exists
     */
    public static function controller(string $dir = null)
    {
        //Load default Controller
        if (!isset($dir)) {
            $controller = new Controller;
            self::setControllerProperties($controller);

            return $controller;
        }

        $class = self::NAMESPACE_CONTROLLER . Str::pathToNamespace($dir);

        if (!class_exists($class)) {
            Log::error("The controller class '$dir' doesn't exists");

            return false;
        }

        $controller = new $class;
        self::setControllerProperties($controller);

        return $controller;
    }


    /**
     * Set the controller properties
     *
     * @param  mixed  $controller  the controller
     */
    private static function setControllerProperties($controller)
    {
        $controller->setLoader(self::loader());
        $controller->setSession(self::session());
        $controller->setUtilities(self::$utilities);
    }


    /**
     * Set the extension properties
     *
     * @param  mixed  $extension  the extension
     */
    private static function setExtensionProperties($extension)
    {
        $extension->load = self::$loader;
        $extension->session = self::$session;

        if (is_array(self::$utilities)) {
            foreach(self::$utilities as $key => $class) {
                $extension->$key = Factory::utility($class);
            }
        }
    }


    /**
     * Returns a extension initialized or false if it doesn't exists
     *
     * @param  string  $name  the extension name
     *
     * @return object|bool a extension initialized or false if it doesn't exists
     */
    public static function extension(string $name)
    {
        $class = self::NAMESPACE_EXTENSION . $name;

        if (!class_exists($class)) {
            Log::error("The extension class '$name' doesn't exists");

            return false;
        }

        $extension = new $class;
        self::setExtensionProperties($extension);

        return $extension;
    }


    /**
     * Returns an utility initialized or false if it doesn't exists
     *
     * @param  string  $name  the utility name
     *
     * @return object|bool an utility initialized or false if it doesn't exists
     */
    public static function utility(string $name)
    {
        $class = self::NAMESPACE_UTILITY . $name;

        if (!class_exists($class)) {
            Log::error("The utility class '$name' doesn't exists");

            return false;
        }

        return new $class;
    }


    /**
     * Adds an utility to the list
     *
     * @param  string  $key  the classname to refer to in the controller
     * @param  string  $class  the classname
     */
    public static function addUtility(string $name, string $class)
    {
        self::$utilities[$name] = $class;
    }


    /**
     * Returns a template class
     *
     * @return Core\Template a template class
     */
    public static function template()
    {
        if (!isset(self::$template)) {
            self::$template = new Template;
        }

        return self::$template;
    }


    /**
     * Returns a session class
     *
     * @return Core\Session a session class
     */
    public static function session()
    {
        if (!isset(self::$session)) {
            self::$session = new Session;
        }

        return self::$session;
    }


    /**
     * Returns a loader class
     *
     * @return Core\Loader a loader class
     */
    public static function loader()
    {
        if (!isset(self::$loader)) {
            self::$loader = new Loader(self::template(), self::session());
        }

        return self::$loader;
    }


    /**
     * Returns a query result as an object
     *
     * @return Core\Query a query result as an object
     */
    public static function query($results)
    {
        return new Query($results);
    }

}
