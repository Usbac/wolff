<?php

namespace Core;

class Cookie
{

    const FIVE_YEARS_TIME = 157680000;
    const MONTH_TIME = 2629743;
    const DAY_TIME = 86400;
    const HOUR_TIME = 3600;

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
     * Set a cookie
     *
     * @param  string  $key  the cookie key
     * @param  mixed  $value  the cookie value
     * @param  mixed  $time  the cookie time
     * @param  string  $path  the path where the cookie will work
     */
    public static function set(string $key, $value, $time, string $path = '/')
    {
        if ($time === 'forever') {
            $time = self::FIVE_YEARS_TIME;
        }

        if ($time === 'month') {
            $time = self::MONTH_TIME;
        }

        if ($time === 'day') {
            $time = self::DAY_TIME;
        }

        setCookie($key, $value, time() + $time, $path);
    }


    /**
     * Unset a cookie
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
