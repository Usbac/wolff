<?php

namespace System;

class Route {

    private static $routes = [];
    private static $blocked = [];


    /**
     * Get the function of a route
     * @param string $url the url
     * @return object the function associated to the route
     */
    public static function get(string $url) {
        $url = explode('/', Library::sanitizeURL($url));
        $urlLength = count($url);

        foreach (self::$routes as $key => $value) {
            $route = explode('/', $key);
            $routeLength = count($route);

            for ($i = 0; $i < $routeLength && $i < $urlLength; $i++) {
                if ($url[$i] != $route[$i] && !preg_match('/\{(.*)?\}/', $route[$i])) {
                    break;
                }

                //Set GET value from any {variable} in the url
                if (preg_match('/\{(.*)?\}/', $route[$i])) {
                    $var = preg_replace('/\{|\}/', '', $route[$i]);
                    $_GET[$var] = $url[$i]?? '';
                }

                //Return route function if last {variable} from url is just empty
                if ($i+2 == $routeLength && $i == $urlLength-1 && preg_match('/\{(.*)?\}/', $route[$i+1])) {
                    $var = preg_replace('/\{|\}/', '', $route[$i+1]);
                    $_GET[$var] = '';
                    return $value;
                }

                //Return route function
                if ($i == $routeLength-1 && $i == $urlLength-1) {
                    return $value;
                }
            }
        }

        return null;
    }


    /**
     * Add a route
     * @param string $url the url
     * @param function $function the function that must be executed when accessing the route
     */
    public static function add(string $url, $function) {
        $url = Library::sanitizeURL($url);
        self::$routes[$url] = $function;
    }


    /**
     * Redirect the first url to the second url
     * @param string $url the first url
     * @param string $url2 the second url
     * @param int $status The response http code
     */
    public static function redirect(string $url, string $url2, int $status = 301) {
        http_response_code($status);
        $url = Library::sanitizeURL($url);
        $url2 = Library::sanitizeURL($url2);
        self::$routes[$url] = self::$routes[$url2];
    }


    /**
     * Block an url
     * @param string $url the url
     */
    public static function block(string $url) {
        $url = Library::sanitizeURL($url);
        array_push(self::$blocked, $url);
    }


    /**
     * Check if an url is blocked
     * @param string $url the url
     * @return boolean true if the url is blocked, false otherwise
     */
    public static function isBlocked(string $url) {
        $url = explode('/', $url);
        $urlLength = count($url);

        foreach (self::$blocked as $key => $value) {
            $blocked = explode('/', self::$blocked[$key]);
            $blockedLength = count($blocked);

            for ($i = 0; $i < $blockedLength && $i < $urlLength; $i++) {
                if ($url[$i] !== $blocked[$i] && $blocked[$i] !== '*') {
                    return false;
                }

                if ($blocked[$i] == '*') {
                    return true;
                }

                if ($i == $urlLength-1 && $i == $blockedLength-1) {
                    return true;
                }
            }
        }

        return false;
    }


    /**
     * Check if a route exists
     * @param string $url the url
     * @return boolean true if the route exists, false otherwise
     */
    public static function exists(string $url) {
        return array_key_exists($url, self::$routes);
    }

}