<?php

namespace Core;

/**
 * Describes the interface of a container that exposes methods to read its entries.
 */
interface ContainerInterface
{

    /**
     * Add a new class
     *
     * @param  string  $class  the class name
     * @param  mixed  $value  the class value
     */
    public static function add(string $class, $value = null);


    /**
     * Add a new singleton class
     *
     * @param  string  $class  the class name
     * @param  mixed  $value  the class value
     */
    public static function singleton(string $class, $value = null);


    /**
     * Finds an entry of the container by its identifier and returns it
     *
     * @param  string  $key  identifier of the entry to look for
     *
     * @return mixed the entry
     */
    public static function get(string $key);


    /**
     * Returns true if the container can return an entry for the given identifier,
     * false otherwise
     *
     * @param  string  $key  identifier of the entry to look for
     *
     * @return bool true if the container can return an entry for the given identifier,
     * false otherwise
     */
    public static function has(string $key);

}
