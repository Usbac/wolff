<?php

namespace Core;

class Route {

    private static $routes = [];
    private static $blocked = [];
    private static $redirects = [];


    /**
     * Get the function of a route
     * @param string $url the url
     * @return object the function associated to the route
     */
    public static function get(string $url) {
        $url = explode('/', sanitizeURL($url));
        $urlLength = count($url);

        foreach (self::$routes as $key => &$function) {
            $route = explode('/', $key);
            $routeLength = count($route);

            for ($i = 0; $i < $routeLength && $i < $urlLength; $i++) {
                if ($url[$i] != $route[$i] && !empty($route[$i]) && !self::isGetVariable($route[$i])) {
                    break;
                }

                //Set GET value from {variable} in the url
                if (self::isGetVariable($route[$i])) {
                    $name = self::clearGetVariable($route[$i]);
                    $_GET[$name] = $url[$i]?? '';
                }

                //Return route function if last {variable} from url is just empty
                if ($i+2 === $routeLength && $i === $urlLength-1 && self::isGetVariable($route[$i+1])) {
                    $name = self::clearGetVariable($route[$i+1]);
                    $_GET[$name] = $url[$i]?? '';
                    return $function;
                }

                //Return route function
                if ($i === $routeLength-1 && $i === $urlLength-1) {
                    return $function;
                }
            }
        }

        return null;
    }


    /**
     * Add a route
     * @param string $url the url
     * @param mixed $function the function that must be executed when accessing the route
     */
    public static function add(string $url, $function) {
        $url = sanitizeURL($url);
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
        $url = sanitizeURL($url);
        $url2 = sanitizeURL($url2);
        self::$routes[$url] = self::$routes[$url2];
        
        self::$redirects[] = array (
            'origin'  => $url,
            'destiny' => $url2,
            'code'    => $status
        );
    }


    /**
     * Block an url
     * @param string $url the url
     */
    public static function block(string $url) {
        $url = sanitizeURL($url);
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

                if ($i === $urlLength-1 && $i === $blockedLength-1) {
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


    /**
     * Check if a string has the format of a route GET variable
     * @param string $str the string
     * @return boolean true if the string has the format of a route GET variable, false otherwise
     */
    private static function isGetVariable(string $str) {
        return preg_match('/\{(.*)?\}/', $str);
    }


    /**
     * Clear a GET string
     * @param string $str the string
     * @return string the get variable without brackets
     */
    private static function clearGetVariable(string $str) {
        return preg_replace('/\{|\}/', '', $str);
    }


    /**
     * Returns all the available routes
     * @return array the available routes
     */
    public static function getRoutes() {
        return self::$routes;
    }
    
    
    /**
     * Returns all the available redirections
     * @return array the available redirections
     */
    public static function getRedirects() {
        return self::$redirects;
    }

    
    /**
     * Returns all the blocked routes
     * @return array the blocked routes
     */
    public static function getBlocked() {
        return self::$blocked;
    }
}