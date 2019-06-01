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


    /**
     * Returns a PDO connection or false in case of errors
     *
     * @param  array  $options  the connection options
     *
     * @return PDO|bool a PDO connection or false in case of errors
     */
    public static function connection(array $options)
    {
        if (isset($options) && !empty($options)) {
            try {
                $connection = new PDO(getDBMS() . ':host=' . getServer() . '; dbname=' . getDB() . '',
                                      getDbUser(), getDbPass(), $options);
            } catch (PDOException $e) {
                Log::critical($e->getMessage());

                return false;
            }

            return $connection;
        }

        return false;
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

        return new $class;
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
     * @return stdClass a query result as an object
     */
    public static function query($results)
    {
        return new class($results)
        {
            /**
             * The query result.
             *
             * @var array
             */
            public $rows;


            public function __construct($results)
            {
                $this->rows = $results;
            }

            /**
             * Returns the first element of the query results
             *
             * @return array the first element of the query results
             */
            public function first()
            {
                return $this->rows[0] ?? null;
            }

            /**
             * Returns the number of rows in the query results
             *
             * @return int the number of rows in the query results
             */
            public function count()
            {
                return count($this->rows);
            }

            /**
             * Returns the query result sliced
             *
             * @param  int  $start  the offset
             * @param  int  $end  the length
             *
             * @return int the query result sliced
             */
            public function limit(int $start, int $end)
            {
                return array_slice($this->rows, $start, $end);
            }
        };
    }

}
