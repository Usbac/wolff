<?php
/**
 * Created by IntelliJ IDEA.
 * User: franklinmoreno
 * Date: 03/03/19
 * Time: 12:22 AM
 */

namespace core;

class routesHelper
{

    public static $routes = [];

    public static function add($url, $controller) {
        //Aqui hay que limpiar la url
        Route::$routes[$url] = $controller;
    }


}