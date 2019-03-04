<?php

namespace core;

require_once 'routesHelper.php';

class Route extends routesHelper
{

    private $controller;
    private $controllerPath;

    public function __construct()
    {

    }

    public function get($url) {
        // $url = explode('/', Library::sanitizeURL($url)); // se limpia la url
        foreach (Route::$routes as $key => $value) {
            $route = explode('/', $key); // separa por /
            for ($i = 0; $i < count($route) && $i < count($url); $i++) {
                if ($url[$i] != $route[$i] && !preg_match('/\{(.*)?\}/', $route[$i])) { // Si esto llega a pasar retorna nulo
                    break;
                }

                //Set GET value from any {variable} in the url
                if (preg_match('/\{(.*)?\}/', $route[$i])) {
                    $var = preg_replace('/\{|\}/', '', $route[$i]);
                    $_GET[$var] = $url[$i]?? '';
                }

                //Return route function if last {variable} from url is just empty
                if ($i+2 == count($route) && $i == count($url)-1 && preg_match('/\{(.*)?\}/', $route[$i+1])) {
                    $var = preg_replace('/\{|\}/', '', $route[$i+1]);
                    $_GET[$var] = '';
                    return $value;
                }

                //Return route function
                if ($i == count($route)-1 && $i == count($url)-1) {
                    return $value;
                }
            }
        }

        return null;
    }

    public function controller($url) {
        //Sanitize directory
        $url = preg_replace('/[^a-zA-Z0-9_\/]/', '', $url);

        //load controller default function and return it
        if ($this->controllerExists($url)) {
            return array(
                "path"          => $this->controllerPath,
                "controller"    => $this->controller[0],
                "method"        => $this->controller[1],
            );
        }

        return array(
            "path"          => "Controller/ElementalController.php",
            "controller"    => "ElementalController",
            "method"        => "notFound",
        );
    }

    /**
     * Checks if the controller exists in the indicated directory
     * @param dir the directory of the controller
     * @return boolean true if the controller exists, false otherwise
     */
    public function controllerExists($url) {

        $this->controller = explode('@', Route::$routes[$url]);

        $this->controllerPath = 'Controller/' . $this->controller[0]. '.php';
        return file_exists($this->controllerPath);
    }

    public function getControllerPath($url) {
        return "Contoller/" . Route::$routes[$url] . ".php";
    }


}