<?php

namespace Core;

class Request 
{

    /**
     * Returns the superglobal GET array or the specified value
     *
     * @param  string  $key  the key
     *
     * @return mixed the superglobal GET array or the specified value
     */
    public static function get(string $key = null) 
    {
        if (!isset($key)) {
            return $_GET;
        }

        return $_GET[$key] ?? null;
    }


    /**
     * Set a GET variable
     *
     * @param  string  $key  the key
     * @param  mixed  $value  the variable value
     */
    public static function setGet(string $key, $value) 
    {
        $_GET[$key] = $value;
    }

    
    /**
     * Unset a GET variable
     *
     * @param  string  $key  the key
     */
    public static function unsetGet(string $key = null) 
    {
        if (!isset($key)) {
            $_GET = [];
        }

        unset($_GET[$key]);
    }


    /**
     * Returns the superglobal POST array or the specified value
     *
     * @param  string  $key  the key
     *
     * @return mixed the superglobal POST array or the specified value
     */
    public static function post(string $key = null) 
    {
        if (!isset($key)) {
            return $_POST;
        }

        return $_POST[$key] ?? null;
    }


    /**
     * Set a POST variable
     *
     * @param  string  $key  the key
     * @param  mixed  $value  the variable value
     */
    public static function setPost(string $key, $value) 
    {
        $_POST[$key] = $value;
    }


    /**
     * Unset a POST variable
     *
     * @param  string  $key  the key
     */
    public static function unsetPost(string $key = null) 
    {
        if (!isset($key)) {
            $_POST = [];
        }

        unset($_POST[$key]);
    }


    /**
     * Returns the superglobal FILES array or the specified value
     *
     * @param  string  $key  the key
     *
     * @return mixed the superglobal FILES array or the specified value
     */
    public static function files(string $key = null) 
    {
        if (!isset($key)) {
            return $_FILES;
        }

        return $_FILES[$key] ?? null;
    }

    
    /**
     * Returns the superglobal COOKIE array or the specified value
     *
     * @param  string  $key  the key
     *
     * @return mixed the superglobal COOKIE array or the specified value
     */
    public static function cookie(string $key = null) 
    {
        if (!isset($key)) {
            return $_COOKIE;
        }

        return $_COOKIE[$key] ?? null;
    }

    
    /**
     * Set a COOKIE variable
     *
     * @param  string  $key  the cookie key
     * @param  mixed  $value  the cookie value
     * @param  mixed  $time  the cookie time
     * @param  string  $path  the path where the cookie will work
     */
    public static function setCookie(string $key, $value, $time, string $path = '/') 
    {
        if ($time === 'forever') {
            $time = 1577880000000;
        }

        if ($time === 'month') {
            $time = 2629743;
        }

        if ($time === 'day') {
            $time = 86400;
        }

        setCookie($key, $value, time() + $time, $path);
    }

        
    /**
     * Unset a COOKIE variable
     *
     * @param  string  $key  the cookie key
     */
    public static function unsetCookie(string $key = null) 
    {
        if (!isset($key)) {
            $_COOKIE = [];
        }

        unset($_COOKIE[$key]);
        setCookie($key, '', time() - 3600);
    }
}