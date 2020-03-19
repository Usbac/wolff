<?php

namespace Wolff\Core;

class Container implements ContainerInterface
{

    /**
     * List of services
     *
     * @var array
     */
    private static $services = [];

    /**
     * List of singletons
     *
     * @var array
     */
    private static $singletons = [];


    /**
     * {@inheritdoc}
     */
    public static function add(string $class, $value = null)
    {
        self::addService($class, $value, false);
    }


    /**
     * {@inheritdoc}
     */
    public static function singleton(string $class, $value = null)
    {
        self::addService($class, $value, true);
    }


    /**
     * Adds a new class
     *
     * @param  string  $class  the class name
     * @param  mixed  $value  the class value
     * @param  bool  $singleton  true if the class will be treated as singleton,
     * false otherwise
     */
    private static function addService(string $class, $value, bool $singleton)
    {
        if (is_null($value)) {
            $value = function() use ($class) {
                return new $class;
            };
        }

        self::$services[$class] = [
            'value'     => $value,
            'singleton' => $singleton
        ];
    }


    /**
     * {@inheritdoc}
     *
     * If no entry is found, null will be returned
     */
    public static function get(string $key, array $args = [])
    {
        if (!self::has($key)) {
            return null;
        }

        //Service
        $service = self::$services[$key];

        if (!$service['singleton']) {
            if (is_callable($service['value'])) {
                return call_user_func_array($service['value'], $args);
            }

            return new $service['value'];
        }

        //Singleton
        if (!isset(self::$singletons[$key])) {
            self::$singletons[$key] = is_callable($service['value']) ?
                call_user_func_array($service['value'], $args) :
                new $service['value'];
        }

        return self::$singletons[$key];
    }


    /**
     * {@inheritdoc}
     */
    public static function has(string $key)
    {
        return array_key_exists($key, self::$services);
    }

}
