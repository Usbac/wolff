<?php

namespace Core;

use Utilities\Str;

class Loader
{

    /**
     * Template manager.
     *
     * @var Core\Template
     */
    private $template;

    /**
     * Session manager.
     *
     * @var Core\Session
     */
    private $session;

    const HEADER_404 = 'HTTP/1.0 404 Not Found';
    const HEADER_503 = 'HTTP/1.1 503 Service Temporarily Unavailable';
    const FUNCTION_SEPARATOR = '@';


    public function __construct($template, $session)
    {
        $this->template = &$template;
        $this->session = &$session;
    }


    /**
     * Returns the session manager
     * @return Core\Session the session
     */
    public function getSession()
    {
        return $this->session;
    }


    /**
     * Load a controller and return it
     *
     * @param  string  $dir  the controller directory
     *
     * @return mixed the controller or false in case of errors
     */
    public function controller(string $dir)
    {
        $controller_path = $dir;
        $dir = Str::sanitizePath($dir);

        //load controller default function and return it
        if (controllerExists($dir)) {
            if (($controller = Factory::controller($dir)) === false) {
                return false;
            }

            if (method_exists($controller, 'index')) {
                $controller->index();
            }

            return $controller;
        }

        //Get a possible function from the url
        $lastSlash = strrpos($dir, '/');
        $function = substr($dir, $lastSlash + 1);
        $dir = substr($dir, 0, $lastSlash);

        //load a controller specified function and return it
        if (controllerExists($dir)) {
            if (($controller = Factory::controller($dir)) === false) {
                return false;
            }

            if (method_exists($controller, $function)) {
                $controller->$function();
            } else {
                Log::error("Controller '$dir' doesn't have a '$function' method");

                return false;
            }

            return $controller;
        }

        Log::error("Controller '$controller_path' doesn't exists");

        return false;
    }


    /**
     * Return the return value of the controller's function or null in case of errors
     *
     * @param  string  $name  the controller's function name
     * Must have the following format: controllerName@functionName
     * @param  mixed  $params  the function arguments
     *
     * @return mixed the return value of the controller's function or null in case of errors
     */
    public function function(string $name, $params = [])
    {
        $params = is_array($params) ? $params : (array)$params;
        $controller_name = Str::before($name, self::FUNCTION_SEPARATOR);
        $controller = Factory::controller($controller_name);
        $function = Str::after($name, self::FUNCTION_SEPARATOR);

        if (method_exists($controller, $function)) {
            return call_user_func_array([$controller, $function], $params);
        }

        Log::error("Controller '$controller_name' doesn't have a '$function' method");

        return null;
    }


    /**
     * Append a function to the controller class and execute it
     *
     * @param  mixed  $closure  the anonymous function
     */
    public function closure($closure)
    {
        if (is_string($closure)) {
            $this->controller($closure);
            return;
        }

        $controller = Factory::controller();
        $closure = $closure->bindTo($controller, $controller);
        $closure();
    }


    /**
     * Load a language and return its content
     *
     * @param  string  $dir  the language directory
     * @param  string  $language  the language selected
     *
     * @return mixed the language content or false if an error happens
     */
    public function language(string $dir, string $language = null)
    {
        $language = $language ?? getLanguage();

        //Sanitize directory
        $dir = Str::sanitizePath($dir);
        $file_path = getAppDirectory() . 'languages/' . $language . '/' . $dir . '.php';

        if (file_exists($file_path)) {
            include_once($file_path);
        } else {
            Log::error("The $language language for '$dir' doesn't exists");

            return false;
        }

        if (!isset($data)) {
            Log::warning("The $language language content for '$dir' is empty");

            return false;
        }

        return $data;
    }


    /**
     * Load a view
     *
     * @param  string  $dir  the view directory
     * @param  array  $data  the view data
     * @param  bool  $cache  use or not the cache system
     */
    public function view(string $dir, array $data = [], bool $cache = true)
    {
        $dir = Str::sanitizePath($dir);
        $file_path = getAppDirectory() . 'views/' . $dir;

        if (!file_exists($file_path . '.php') && !file_exists($file_path . '.html')) {
            Log::error("View '$dir' doesn't exists");

            return;
        }

        $this->template->get($dir, $data, $cache);
    }


    /**
     * Get a view content
     *
     * @param  string  $dir  the view directory
     * @param  array  $data  the data
     *
     * @return mixed the view or false in case of errors
     */
    public function getView(string $dir, array $data = [])
    {
        $dir = Str::sanitizePath($dir);
        $file_path = getAppDirectory() . 'views/' . $dir;

        if (!file_exists($file_path . '.php') && !file_exists($file_path . '.html')) {
            Log::error("View '$dir' doesn't exists");

            return false;
        }

        return $this->template->getView($dir, $data);
    }


    /**
     * Load the 404 view page
     */
    public function redirect404()
    {
        header(self::HEADER_404);
        $this->controller('_404');
        exit;
    }


    /**
     * Load the maintenance view page
     */
    public function maintenance()
    {
        header(self::HEADER_503);
        $this->controller('_maintenance');
        exit;
    }

}
