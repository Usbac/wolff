<?php

namespace Core;

use Utilities\Str;

class Route
{

    /**
     * List of routes.
     *
     * @var array
     */
    private static $routes = [];

    /**
     * List of blocked routes.
     *
     * @var array
     */
    private static $blocked = [];

    /**
     * List of redirects.
     *
     * @var array
     */
    private static $redirects = [];


    const STATUS_OK = 200;
    const STATUS_REDIRECT = 301;
    const GET_FORMAT = '/\{(.*)\}/';
    const OPTIONAL_GET_FORMAT = '/\{(.*)\?\}/';
    const HTTP_METHODS = [
        'GET',
        'POST',
        'PUT',
        'PATCH',
        'DELETE'
    ];


    /**
     * Proxy function to the HTTP Methods.
     *
     * @param  string  $name the function name
     * @param  mixed  $args  the function arguments
     */
    public static function __callStatic(string $name, $args)
    {
        $method = strtoupper($name);
        if (!in_array($method, self::HTTP_METHODS)) {
            return;
        }

        $status = isset($args[2]) && is_numeric($args[2]) ?
            (int)$args[2] : self::STATUS_OK;

        $url = Str::sanitizeURL($args[0]);
        self::addRoute($url, $method, $args[1], $status);
    }


    /**
     * Returns the function of a route
     *
     * @param  string  $url  the url
     *
     * @return object the function associated to the route
     */
    public static function getFunc(string $url)
    {
        $current = explode('/', Str::sanitizeURL($url));
        $current_length = count($current) - 1;

        if (empty(self::$routes)) {
            return null;
        }

        foreach (self::$routes as $key => $val) {
            $route = explode('/', $key);
            $route_length = count($route) - 1;

            for ($i = 0; $i <= $route_length && $i <= $current_length; $i++) {
                if ($current[$i] != $route[$i] && !empty($route[$i]) &&
                    !self::isGetVar($route[$i]) && !self::isOptionalGetVar($route[$i])) {
                    break;
                }

                //Set GET variables from url
                if (self::isGetVar($route[$i])) {
                    self::setGetVar($route[$i], $current[$i]);
                } else if (self::isOptionalGetVar($route[$i])) {
                    self::setOptionalGetVar($route[$i], $current[$i]);
                }

                //Finish if last GET variable from url is optional
                if ($i + 1 === $route_length && $i === $current_length &&
                    self::isOptionalGetVar($route[$i + 1]) && self::isValidRoute($key)) {
                    self::setOptionalGetVar($route[$i], $current[$i]);
                    http_response_code($val['status']);
                    return $val['function'];
                }

                //Process the route
                if ($i === $route_length && $i === $current_length && self::isValidRoute($key)) {
                    http_response_code($val['status']);
                    return $val['function'];
                }
            }
        }

        return null;
    }


    /**
     * Returns true if the route exists and it's
     * request method matches the current methods
     *
     * @param  string  $key  the route key
     * @return boolean true if the route exists and it's
     * request method matches the current methods
     */
    private static function isValidRoute($key)
    {
        return (self::$routes[$key] &&
            Request::matchesMethod(self::$routes[$key]['method']));
    }


    /**
     * Add a route with get method
     *
     * @param  string  $url  the url
     * @param  mixed  $function  mixed the function that must be executed when accessing the route
     * @param  int  $status  the HTTP status code
     */
    public static function add(string $url, $function, int $status = self::STATUS_OK)
    {
        $url = Str::sanitizeURL($url);
        self::addRoute($url, 'GET', $function, $status);
    }


    /**
     * Redirect the first url to the second url
     *
     * @param  string  $url  the first url
     * @param  string  $url2  the second url
     * @param  int  $status  The HTTP response code
     */
    public static function redirect(string $url, string $url_2, int $status = self::STATUS_REDIRECT)
    {
        $url = Str::sanitizeURL($url);
        $url_2 = Str::sanitizeURL($url_2);

        //Get the controller default route if the second url isn't a defined custom route
        $function = isset(self::$routes[$url_2]) ?
            self::$routes[$url_2]['function'] : $url_2;

        self::addRoute($url, self::$routes[$url_2]['method'], $function, $status);
        self::addRedirect($url, $url_2, $status);
    }


    /**
     * Add a route to the list
     *
     * @param  mixed  $url  the url
     * @param  mixed  $function  the url function or controller name
     * @param  bool  $api  is or not an api route
     * @param  int  $status  the HTTP status code
     */
    private static function addRoute($url, string $method, $function, int $status) {
        self::$routes[$url] = [
            'function' => $function,
            'method'   => $method,
            'status'   => $status
        ];
    }


    /**
     * Add a redirection to the list
     *
     * @param  mixed  $url  the origin url
     * @param  mixed  $url2  the destiny url
     * @param  int  $status  the HTTP status code
     */
    private static function addRedirect($url, $url2, int $status) {
        self::$redirects[$url] = [
            'destiny' => $url2,
            'code'    => $status
        ];
    }


    /**
     * Block an url
     *
     * @param  string  $url  the url
     */
    public static function block(string $url)
    {
        array_push(self::$blocked, Str::sanitizeURL($url));
    }


    /**
     * Check if an url is blocked
     *
     * @param  string  $url  the url
     *
     * @return boolean true if the url is blocked, false otherwise
     */
    public static function isBlocked(string $url)
    {
        $url = explode('/', $url);
        $url_length = count($url);

        foreach (self::$blocked as $blocked) {
            $blocked = explode('/', $blocked);
            $blocked_length = count($blocked);

            for ($i = 0; $i < $blocked_length && $i < $url_length; $i++) {
                if ($url[$i] !== $blocked[$i] && $blocked[$i] !== '*') {
                    return false;
                }

                if ($blocked[$i] === '*') {
                    return true;
                }

                if ($i === $url_length - 1 && $i === $blocked_length - 1) {
                    return true;
                }
            }
        }

        return false;
    }


    /**
     * Check if a route exists
     *
     * @param  string  $url  the url
     *
     * @return boolean true if the route exists, false otherwise
     */
    public static function exists(string $url)
    {
        $url = preg_replace(self::GET_FORMAT, '{}', $url);
        $routes = [];

        foreach (array_keys(self::$routes) as $key) {
            $routes[] = preg_replace(self::GET_FORMAT, '{}', $key);
        }

        return in_array($url, $routes);
    }


    /**
     * Returns true if a string has the format of a GET variable, false otherwise
     *
     * @param  string  $str  the string
     *
     * @return boolean true if the string has the format of a route GET variable, false otherwise
     */
    public static function isGetVar(string $str)
    {
        return preg_match(self::GET_FORMAT, $str);
    }


    /**
     * Returns true if a string has the format of an optional GET variable, false otherwise
     *
     * @param  string  $str  the string
     *
     * @return boolean true if the string has the format of an optional route GET variable, false otherwise
     */
    public static function isOptionalGetVar(string $str)
    {
        return preg_match(self::OPTIONAL_GET_FORMAT, $str);
    }


    /**
     * Set a GET variable
     *
     * @param  string  $key  the variable key
     * @param  string  $value  the variable value
     */
    private static function setGetVar(string $key, $value)
    {
        $key = preg_replace(self::GET_FORMAT, '$1', $key);
        Request::setGet($key, $value);
    }


    /**
     * Set an optional GET variable
     *
     * @param  string  $key  the variable key
     * @param  string  $value  the variable value
     */
    private static function setOptionalGetVar(string $key, $value = null)
    {
        $key = preg_replace(self::OPTIONAL_GET_FORMAT, '$1', $key);
        Request::setGet($key, $value ?? '');
    }


    /**
     * Returns all the available routes
     * @return array the available routes
     */
    public static function getRoutes()
    {
        return self::$routes;
    }


    /**
     * Returns all the available redirects
     * @return array the available redirects
     */
    public static function getRedirects()
    {
        return self::$redirects;
    }


    /**
     * Returns the redirection of the specified route
     *
     * @param  string  $url  the route url
     *
     * @return string|null the redirection url
     * or null if the specified route doesn't have a redirection
     */
    public static function getRedirection(string $url)
    {
        if (!isset(self::$redirects[$url])) {
            return null;
        }

        return self::$redirects[$url]['destiny'] ?? null;
    }


    /**
     * Returns all the blocked routes
     * @return array the blocked routes
     */
    public static function getBlocked()
    {
        return self::$blocked;
    }
}
