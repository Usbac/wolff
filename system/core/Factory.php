<?php

namespace Core;

use Utilities\Str;
use PDO, PDOException;

class Factory
{

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
            return new Controller;
        }

        $class = self::NAMESPACE_CONTROLLER . Str::pathToNamespace($dir);

        if (!class_exists($class)) {
            Log::error("The controller class '$dir' doesn't exists");

            return false;
        }

        return new $class;
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
     * @return object|bool a utility initialized or false if it doesn't exists
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

}
