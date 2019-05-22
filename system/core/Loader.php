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

    /**
     * File uploader utility.
     *
     * @var Utilities\Upload
     */
    private $upload;

    const HEADER_404 = "HTTP/1.0 404 Not Found";
    const HEADER_503 = "HTTP/1.1 503 Service Temporarily Unavailable";


    public function __construct($template, $session, $upload)
    {
        $this->template = &$template;
        $this->session = &$session;
        $this->upload = &$upload;
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
     * Returns the uploader
     * @return Lib\Upload the uploader
     */
    public function getUpload()
    {
        return $this->upload;
    }


    /**
     * Load a controller and return it
     *
     * @param  string  $dir  the controller directory
     *
     * @return object the controller
     */
    public function controller(string $dir)
    {
        $controller_path = $dir;
        $dir = Str::sanitizePath($dir);

        //load controller default function and return it
        if (controllerExists($dir)) {
            $controller = Factory::controller($dir);
            $this->setControllerProps($controller);

            if ($controller === false) {
                return false;
            }

            $controller->index();

            return $controller;
        }

        //Get a possible function from the url
        $lastSlash = strrpos($dir, '/');
        $function = substr($dir, $lastSlash + 1);
        $dir = substr($dir, 0, $lastSlash);

        //load a controller specified function and return it
        if (controllerExists($dir)) {
            $controller = Factory::controller($dir);
            $this->setControllerProps($controller);

            if ($controller === false) {
                return false;
            }

            $controller->$function();

            return $controller;
        }

        Log::error("Controller '$controller_path' doesn't exists");

        return false;
    }


    /**
     * Set the controller properties
     *
     * @param  object  $controller  the controller
     */
    private function setControllerProps(object $controller)
    {
        $controller->setLoader($this);
        $controller->setSession($this->session);
        $controller->setUploader($this->upload);
    }


    /**
     * Append a function to the controller class and execute it
     *
     * @param  \Closure  $closure  the anonymous function
     */
    public function closure(\Closure $closure)
    {
        $controller = Factory::controller();
        $this->setControllerProps($controller);

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
    public function language(string $dir, string $language = WOLFF_LANGUAGE)
    {
        //Sanitize directory
        $dir = Str::sanitizePath($dir);
        $file_path = getAppDirectory() . 'languages/' . $language . '/' . $dir . '.php';

        if (file_exists($file_path)) {
            include_once($file_path);
        } else {
            Log::warning("The $language language for '$dir' doesn't exists");

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
     * @return string the view
     */
    public function getView(string $dir, array $data = [])
    {
        $dir = Str::sanitizePath($dir);
        $file_path = getAppDirectory() . 'views/' . $dir;

        if (!file_exists($file_path . '.php') && !file_exists($file_path . '.html')) {
            Log::error("View '$dir' doesn't exists");

            return;
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
        die();
    }


    /**
     * Load the maintenance view page
     */
    public function maintenance()
    {
        header(self::HEADER_503);
        $this->controller('_maintenance');
        die();
    }

}
