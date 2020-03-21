<?php

namespace Wolff\Core;

use Wolff\Utils\Str;

class Route
{

    const STATUS_OK = 200;
    const STATUS_REDIRECT = 301;
    const GET_FORMAT = '/\{(.*)\}/';
    const OPTIONAL_GET_FORMAT = '/\{(.*)\?\}/';
    const PREFIXES = [
        'csv'   => 'text/csv',
        'json'  => 'application/json',
        'pdf'   => 'application/pdf',
        'plain' => 'text/plain',
        'xml'   => 'application/xml'
    ];
    const HTTP_METHODS = [
        'GET',
        'POST',
        'PUT',
        'PATCH',
        'DELETE'
    ];

    /**
     * List of routes.
     *
     * @var array
     */
    private static $routes = [];

    /**
     * List of routes for status codes.
     */
    private static $codes = [];

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


    /**
     * Proxy method to the HTTP Methods.
     *
     * @param  string  $name the method name
     * @param  mixed  $args  the method arguments
     */
    public static function __callStatic(string $name, $args)
    {
        $http_method = strtoupper($name);
        if (!in_array($http_method, self::HTTP_METHODS)) {
            return;
        }

        $url = Str::sanitizeURL($args[0]);
        $function = $args[1];
        $status = isset($args[2]) && is_numeric($args[2]) ?
            (int)$args[2] :
            null;

        self::addRoute($url, $http_method, $function, $status);
    }


    /**
     * Adds a route that will work
     * only for a status code
     *
     * @param  string  $code  the status code
     * @param  mixed  $function  mixed the function that must be executed
     * when getting the status code
     */
    public static function code(string $code, $function)
    {
        self::$codes[trim($code)] = $function;
    }


    /**
     * Executes a code route based on the current status code
     */
    public static function execCode()
    {
        $code = http_response_code();

        if (isset(self::$codes[$code]) &&
            is_callable(self::$codes[$code])) {
            self::$codes[$code]();
        }
    }


    /**
     * Returns the value of a route
     *
     * @param  string  $url  the url
     *
     * @return mixed the value associated to the route
     */
    public static function getVal(string $url)
    {
        $current = array_filter(explode('/', $url));
        $current_length = count($current) - 1;

        if (empty(self::$routes)) {
            return null;
        }

        foreach (self::$routes as $key => $val) {
            if (!self::isValidRoute($key)) {
                continue;
            }

            $route = array_filter(explode('/', $key));
            $route_length = count($route) - 1;

            if (empty($current) && empty($route)) {
                return self::processRoute($current, $route);
            }

            for ($i = 0; $i <= $route_length && $i <= $current_length; $i++) {
                if ($current[$i] !== $route[$i] && !self::isGet($route[$i])) {
                    break;
                }

                if (($i === $route_length || ($i + 1 === $route_length && self::isOptionalGet($route[$i + 1]))) &&
                    $i === $current_length) {
                    return self::processRoute($current, $route);
                }
            }
        }

        return null;
    }


    /**
     * Returns the route function after
     * setting the HTTP response code and the content-type
     * based on the route
     *
     * @param  array  $current  the current route array (exploded by /)
     *
     * @param  array  $route  the registered route array which matches the
     * current route (exploded by /)
     *
     * @return mixed the route function
     */
    private static function processRoute(array $current, array $route)
    {
        self::mapParameters($current, $route);

        $route = self::$routes[implode('/', $route)];
        header("Content-Type: $route[content_type]");
        if (isset($route['status'])) {
            http_response_code($route['status']);
        }

        return $route['function'];
    }


    /**
     * Maps the current route GET parameters
     *
     * @param  array  $current  the current route array
     * (exploded by /)
     *
     * @param  array  $route  the registered route array
     * (exploded by /) which matches the current route
     */
    private static function mapParameters(array $current, array $route)
    {
        $current_length = count($current) - 1;
        $route_length = count($route) - 1;

        for ($i = 0; $i <= $route_length && $i <= $current_length; $i++) {
            if (self::isOptionalGet($route[$i])) {
                self::setOptionalGetVar($route[$i], $current[$i]);
            } else if (self::isGet($route[$i])) {
                self::setGet($route[$i], $current[$i]);
            }

            //Finish if last GET variable from url is optional
            if ($i + 1 === $route_length && $i === $current_length &&
                self::isOptionalGet($route[$i + 1])) {
                self::setOptionalGetVar($route[$i], $current[$i]);
                return;
            }
        }
    }


    /**
     * Returns true if the route exists and its
     * request method matches the current methods
     *
     * @param  string  $key  the route key
     * @return boolean true if the route exists and its
     * request method matches the current methods
     */
    private static function isValidRoute($key)
    {
        return self::$routes[$key] &&
            in_array(self::$routes[$key]['method'], [ '', $_SERVER['REQUEST_METHOD']]);
    }


    /**
     * Adds a route with get method
     *
     * @param  string  $url  the url
     * @param  mixed  $function  mixed the function that must be executed when accessing the route
     * @param  int  $status  the HTTP response code
     */
    public static function add(string $url, $function, int $status = self::STATUS_OK)
    {
        self::addRoute(Str::sanitizeURL($url), '', $function, $status);
    }


    /**
     * Redirects the first url to the second url
     *
     * @param  string  $from  the origin url
     * @param  string  $to  the destiny url
     * @param  int  $code  The HTTP response code
     */
    public static function redirect(string $from, string $to, int $code = self::STATUS_REDIRECT)
    {
        self::$redirects[Str::sanitizeURL($from)] = [
            'destiny' => Str::sanitizeURL($to),
            'code'    => $code
        ];
    }


    /**
     * Adds a route to the list
     *
     * @param  mixed  $url  the url
     * @param  string  $method  the url HTTP method
     * @param  mixed  $function  the url function or controller name
     * @param  int  $status  the HTTP response code
     */
    private static function addRoute($url, string $method, $function, $status) {
        $content_type = 'text/html';

        //Remove content-type prefix from route
        foreach (self::PREFIXES as $key => $val) {
            $prefix_key = "$key:";

            if (strpos($url, $prefix_key) === 0) {
                $url = substr($url, strpos($url, $prefix_key) + strlen($prefix_key));
                $content_type = $val;
            }
        }

        self::$routes[$url] = [
            'function'     => $function,
            'method'       => $method,
            'status'       => $status,
            'content_type' => $content_type
        ];
    }


    /**
     * Blocks an url
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
        $url_length = count($url) - 1;

        foreach (self::$blocked as $blocked) {
            $blocked = explode('/', $blocked);
            $blocked_length = count($blocked) - 1;

            for ($i = 0; $i <= $blocked_length && $i <= $url_length; $i++) {
                if (!in_array($blocked[$i], [ '*', $url[$i] ])) {
                    break;
                }

                if ($blocked[$i] === '*' ||
                    ($i === $url_length && $i === $blocked_length)) {
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
    private static function isGet(string $str)
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
    private static function isOptionalGet(string $str)
    {
        return preg_match(self::OPTIONAL_GET_FORMAT, $str);
    }


    /**
     * Set a GET variable
     *
     * @param  string  $key  the variable key
     * @param  string  $value  the variable value
     */
    private static function setGet(string $key, $value)
    {
        $key = preg_replace(self::GET_FORMAT, '$1', $key);
        $_GET[$key] = $value;
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
        $_GET[$key] = $value ?? '';
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

        return self::$redirects[$url];
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
