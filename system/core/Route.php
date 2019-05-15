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


    /**
     * Get the function of a route
     *
     * @param  string  $url  the url
     *
     * @return object the function associated to the route
     */
    public static function get(string $url)
    {
        $url = explode('/', Str::sanitizeURL($url));
        $urlLength = count($url) - 1;
        $finished = false;

        if (self::$routes === []) {
            return null;
        }

        foreach (self::$routes as $key => $value) {
            $route       = explode('/', $key);
            $routeLength = count($route) - 1;

            for ($i = 0; $i <= $routeLength && $i <= $urlLength; $i++) {
                if ($url[$i] != $route[$i] && !empty($route[$i]) && !self::isGetVariable($route[$i])) {
                    break;
                }

                //Set GET variable from the url
                if (self::isGetVariable($route[$i])) {
                    self::setGetVariable($route[$i], $url[$i]);
                }

                //Finish if last GET variable from url is empty
                if ($i + 1 === $routeLength && $i === $urlLength && self::isGetVariable($route[$i + 1])) {
                    self::setGetVariable($route[$i], $url[$i]);
                    $finished = true;
                }

                //Process the route and return its function
                if ($finished || ($i === $routeLength && $i === $urlLength)) {
                    self::processRoute($key);

                    return self::$routes[$key]['function'];
                }
            }
        }

        return null;
    }


    /**
     * Apply the response code and content type of a route
     *
     * @param  string  $key  the route key
     */
    private static function processRoute($key)
    {
        if (!self::$routes[$key]) {
            return;
        }

        if (self::$routes[$key]['api']) {
            header('Content-Type: application/json');
        }

        http_response_code(self::$routes[$key]['status']);
    }


    /**
     * Add a route
     *
     * @param  string  $url  the url
     * @param $function mixed the function that must be executed when accessing the route
     * @param  int  $status the HTTP status code
     */
    public static function add(string $url, $function, int $status = self::STATUS_OK)
    {
        $url = Str::sanitizeURL($url);

        //Use $function as a controller name and load it if it's a string
        if (is_string($function)) {
            $function = self::getControllerClosure($function);
        }

        self::$routes[$url] = array(
            'function' => $function,
            'api'      => false,
            'status'   => $status
        );
    }


    /**
     * Returns the closure that loads a controller
     *
     * @param  string  $controller  the controller
     *
     * @return closure The closure that loads a controller
     */
    private function getControllerClosure(string $controller)
    {
        return function () use ($controller) {
            $this->load->controller($controller);
        };
    }


    /**
     * Add an API
     *
     * @param  string  $url  the url
     * @param  mixed  $function mixed the function that must be executed when accessing the API
     * @param  int  $status  the http response code
     */
    public static function api(string $url, $function, int $status = self::STATUS_OK)
    {
        $url = Str::sanitizeURL($url);
        self::$routes[$url] = array(
            'function' => $function,
            'api'      => true,
            'status'   => $status,
        );
    }


    /**
     * Redirect the first url to the second url
     *
     * @param  string  $url  the first url
     * @param  string  $url2  the second url
     * @param  int  $status  The HTTP response code
     */
    public static function redirect(string $url, string $url2, int $status = self::STATUS_REDIRECT)
    {
        $url = Str::sanitizeURL($url);
        $url2 = Str::sanitizeURL($url2);

        self::$routes[$url] = array(
            'function' => self::$routes[$url2]['function'],
            'api'      => false,
            'status'   => $status,
        );

        self::$redirects[] = array(
            'origin'  => $url,
            'destiny' => $url2,
            'code'    => $status,
        );
    }


    /**
     * Block an url
     *
     * @param  string  $url  the url
     */
    public static function block(string $url)
    {
        $url = Str::sanitizeURL($url);
        array_push(self::$blocked, $url);
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
        $urlLength = count($url);

        foreach (self::$blocked as $blocked) {
            $blocked = explode('/', $blocked);
            $blockedLength = count($blocked);

            for ($i = 0; $i < $blockedLength && $i < $urlLength; $i++) {
                if ($url[$i] !== $blocked[$i] && $blocked[$i] !== '*') {
                    return false;
                }

                if ($blocked[$i] === '*') {
                    return true;
                }

                if ($i === $urlLength - 1 && $i === $blockedLength - 1) {
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
     * Check if a string has the format of a route GET variable
     *
     * @param  string  $str  the string
     *
     * @return boolean true if the string has the format of a route GET variable, false otherwise
     */
    public static function isGetVariable(string $str)
    {
        return preg_match('/\{(.*)?\}/', $str);
    }


    /**
     * Clear a GET string
     *
     * @param  string  $str  the string
     *
     * @return string the get variable without brackets
     */
    public static function clearGetVariable(string $str)
    {
        return preg_replace('/\{|\}/', '', $str);
    }


    /**
     * Set a GET variable
     *
     * @param  string  $key  the variable key
     * @param  string  $value  the variable value
     */
    private static function setGetVariable(string $key, $value = '')
    {
        $key = self::clearGetVariable($key);
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
     * Returns all the blocked routes
     * @return array the blocked routes
     */
    public static function getBlocked()
    {
        return self::$blocked;
    }
}