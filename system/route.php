<?php

class Route {

    private static $routes = [];
    private static $blocked = [];


    /**
     * Get the function of a route
     * @param url the url
     * @return object the function associated to the route
     */
    public static function get($url) {
        $url = explode('/', Library::sanitizeURL($url));

        foreach (Route::$routes as $key => $value) {
            $route = explode('/', $key);

            for ($i = 0; $i < count($route) && $i < count($url); $i++) {
                if ($url[$i] != $route[$i] && !preg_match('/\{(.*)?\}/', $route[$i])) {
                    break;
                }

                //Set GET value from any {variable} in the url
                if (preg_match('/\{(.*)?\}/', $route[$i])) {
                    $var = preg_replace('/\{|\}/', '', $route[$i]);
                    $_GET[$var] = $url[$i];
                }

                //Return route function
                if ($i == count($route)-1 && $i == count($url)-1) {
                    return $value;
                }
            }
        }

        return null;
    }


    /**
     * Add a route
     * @param url the url
     * @param function the function that must be executed when accessing the route
     */
    public static function add($url, $function) {
        $url = Library::sanitizeURL($url);
        Route::$routes[$url] = $function;
    }


    /**
     * Redirect the first url to the second url
     * @param url the first url
     * @param url2 the second url
     * @param status The response http code
     */
    public static function redirect($url, $url2, $status = 301) {
        http_response_code($status);
        $url = Library::sanitizeURL($url);
        $url2 = Library::sanitizeURL($url2);
        Route::$routes[$url] = Route::$routes[$url2];
    }


    /**
     * Block an url
     * @param url the url
     */
    public static function block($url) {
        $url = Library::sanitizeURL($url);
        array_push(Route::$blocked, $url);
    }


    /**
     * Check if an url is blocked
     * @param url the url
     * @return boolean true if the url is blocked, false otherwise
     */
    public static function isBlocked($url) {
        $url = explode('/', $url);

        foreach (Route::$blocked as $key => $value) {
            $blocked = explode('/', Route::$blocked[$key]);

            for ($i = 0; $i < count($blocked) && $i < count($url); $i++) {
                if ($url[$i] !== $blocked[$i] && $blocked[$i] !== '*') {
                    return false;
                }

                if ($blocked[$i] == '*') {
                    return true;
                }

                if ($i == count($url)-1 && $i == count($blocked)-1) {
                    return true;
                }
            }
        }

        return false;
    }


    /**
     * Check if a route exists
     * @param url the url
     * @return boolean true if the route exists, false otherwise
     */
    public static function exists($url) {
        return array_key_exists($url, Route::$routes);
    }

}