<?php

namespace Core;

class Request
{

    const FIVE_YEARS_TIME = 157680000;
    const MONTH_TIME = 2629743;
    const DAY_TIME = 86400;
    const HOUR_TIME = 3600;
    const ROOT_PATH = '/';
    const PHP_INPUT = 'php://input';


    /**
     * Returns the current HTTP method
     *
     * @return string the current HTTP method
     */
    public static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }


    /**
     * Returns true if the given HTTP method is equal to
     * the current method, false otherwise
     *
     * @param  string  $method  the HTTP method to compare
     *
     * @return string true if the given HTTP method is equal to
     * the current method, false otherwise
     */
    public static function matchesMethod(string $method)
    {
        return $_SERVER['REQUEST_METHOD'] === strtoupper($method);
    }


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
        } elseif (!self::hasGet($key)) {
            Log::notice("Undefined index '$key' for Request::get");
        }

        return $_GET[$key] ?? null;
    }


    /**
     * Returns true if the GET variable exists, false otherwise
     *
     * @param  string  $key  the variable key
     *
     * @return bool true if the GET variable exists, false otherwise
     */
    public static function hasGet(string $key)
    {
        return array_key_exists($key, $_GET);
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
        } elseif (!self::hasPost($key)) {
            Log::notice("Undefined index '$key' for Request::post");
        }

        return $_POST[$key] ?? null;
    }


    /**
     * Returns true if the POST variable exists, false otherwise
     *
     * @param  string  $key  the variable key
     *
     * @return bool true if the POST variable exists, false otherwise
     */
    public static function hasPost(string $key)
    {
        return array_key_exists($key, $_POST);
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
     * Returns the PUT array or the specified value
     *
     * @param  string  $key  the key
     *
     * @return mixed the PUT array or the specified value
     */
    public static function put(string $key = null)
    {
        parse_str(file_get_contents(Self::PHP_INPUT), $_PUT);

        if (!isset($key)) {
            return $_PUT;
        } elseif (!self::hasPost($key)) {
            Log::notice("Undefined index '$key' for Request::put");
        }

        return $_PUT[$key] ?? null;
    }


    /**
     * Returns true if the PUT variable exists, false otherwise
     *
     * @param  string  $key  the variable key
     *
     * @return bool true if the PUT variable exists, false otherwise
     */
    public static function hasPut(string $key = null)
    {
        parse_str(file_get_contents(Self::PHP_INPUT), $_PUT);
        return array_key_exists($key, $_POST);
    }


   /**
     * Returns the PATCH array or the specified value
     *
     * @param  string  $key  the key
     *
     * @return mixed the PATCH array or the specified value
     */
    public static function patch(string $key = null)
    {
        parse_str(file_get_contents(Self::PHP_INPUT), $_PATCH);

        if (!isset($key)) {
            return $_PATCH;
        } elseif (!self::hasPatch($key)) {
            Log::notice("Undefined index '$key' for Request::patch");
        }

        return $_PATCH[$key] ?? null;
    }


    /**
     * Returns true if the PATCH variable exists, false otherwise
     *
     * @param  string  $key  the variable key
     *
     * @return bool true if the PATCH variable exists, false otherwise
     */
    public static function hasPatch(string $key = null)
    {
        parse_str(file_get_contents(Self::PHP_INPUT), $_PATCH);
        return array_key_exists($key, $_PATCH);
    }


   /**
     * Returns the DELETE array or the specified value
     *
     * @param  string  $key  the key
     *
     * @return mixed the DELETE array or the specified value
     */
    public static function delete(string $key = null)
    {
        parse_str(file_get_contents(Self::PHP_INPUT), $_DELETE);

        if (!isset($key)) {
            return $_DELETE;
        } elseif (!self::hasDelete($key)) {
            Log::notice("Undefined index '$key' for Request::delete");
        }

        return $_DELETE[$key] ?? null;
    }


    /**
     * Returns true if the DELETE variable exists, false otherwise
     *
     * @param  string  $key  the variable key
     *
     * @return bool true if the DELETE variable exists, false otherwise
     */
    public static function hasDelete(string $key = null)
    {
        parse_str(file_get_contents(Self::PHP_INPUT), $_DELETE);
        return array_key_exists($key, $_DELETE);
    }


    /**
     * Returns the superglobal FILES array or the specified value
     *
     * @param  string  $key  the key
     *
     * @return mixed the superglobal FILES array or the specified value
     */
    public static function file(string $key = null)
    {
        if (!isset($key)) {
            return $_FILES;
        } elseif (!self::hasFile($key)) {
            Log::notice("Undefined index '$key' for Request::file");
        }

        return $_FILES[$key] ?? null;
    }


    /**
     * Returns true if the FILE variable exists, false otherwise
     *
     * @param  string  $key  the variable key
     *
     * @return bool true if the FILE variable exists, false otherwise
     */
    public static function hasFile(string $key)
    {
        return array_key_exists($key, $_FILES);
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
        } elseif (!self::hasCookie($key)) {
            Log::notice("Undefined index '$key' for Request::cookie");
        }

        return $_COOKIE[$key] ?? null;
    }


    /**
     * Returns true if the COOKIE variable exists, false otherwise
     *
     * @param  string  $key  the variable key
     *
     * @return bool true if the COOKIE variable exists, false otherwise
     */
    public static function hasCookie(string $key)
    {
        return array_key_exists($key, $_COOKIE);
    }


    /**
     * Set a COOKIE variable
     *
     * @param  string  $key  the cookie key
     * @param  mixed  $value  the cookie value
     * @param  mixed  $time  the cookie time
     * @param  string  $path  the path where the cookie will work
     */
    public static function setCookie(string $key, $value, $time, string $path = self::ROOT_PATH)
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
     * Unset a COOKIE variable
     *
     * @param  string  $key  the cookie key
     */
    public static function unsetCookie(string $key = null)
    {
        if (!isset($key)) {
            $_COOKIE = [];
            return;
        }

        unset($_COOKIE[$key]);
        setCookie($key, '', time() - self::HOUR_TIME);
    }
}
