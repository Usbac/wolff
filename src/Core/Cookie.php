<?php

namespace Wolff\Core;

class Cookie
{

    const TIME = [
        'FOREVER' => 157680000, // Five years
        'MONTH'   => 2629743,
        'DAY'     => 86400,
        'HOUR'    => 3600
    ];


    /**
     * Returns the cookies or the specified cookie
     *
     * @param  string  $key  the key
     *
     * @return mixed the cookies or the specified cookie
     */
    public static function get(string $key = null)
    {
        if (!isset($key)) {
            return $_COOKIE;
        }

        return $_COOKIE[$key] ?? null;
    }


    /**
     * Returns true if the cookie exists, false otherwise
     *
     * @param  string  $key  the variable key
     *
     * @return bool true if the cookie exists, false otherwise
     */
    public static function has(string $key)
    {
        return array_key_exists($key, $_COOKIE);
    }


    /**
     * Sets a cookie
     *
     * @param  string  $key  the cookie key
     * @param  mixed  $value  the cookie value
     * @param  mixed  $time  the cookie time
     * @param  string  $path  the path where the cookie will work
     */
    public static function set(string $key, $value, $time, string $path = '/')
    {
        if (is_string($time)) {
            $time = \strtoupper($time);
        }

        if (array_key_exists($time, self::TIME)) {
            $time = self::TIME[$time];
        }

        setCookie($key, $value, time() + $time, $path);
    }


    /**
     * Removes a cookie
     *
     * @param  string  $key  the cookie key
     */
    public static function unset(string $key)
    {
        if (!isset($key)) {
            $_COOKIE = [];
            return;
        }

        unset($_COOKIE[$key]);
        setCookie($key, '', time() - self::HOUR_TIME);
    }

}
