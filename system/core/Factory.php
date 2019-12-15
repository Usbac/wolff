<?php

namespace Core;

use Utilities\Str;
use PDO, PDOException;

class Factory
{

    const NAMESPACE_CONTROLLER = 'Controller\\';
    const NAMESPACE_MIDDLEWARE = 'Middleware\\';
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
        if (empty($options) || empty(getDB())) {
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
     * Returns a middleware initialized or false if it doesn't exists
     *
     * @param  string  $name  the middleware name
     *
     * @return object|bool a middleware initialized or false if it doesn't exists
     */
    public static function middleware(string $name)
    {
        $class = self::NAMESPACE_MIDDLEWARE . $name;

        if (!class_exists($class)) {
            Log::error("The middleware class '$name' doesn't exists");

            return false;
        }

        return new $class;
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
