<?php

namespace Core;

use Utilities\Str;

class Start
{

    /**
     * The current url.
     *
     * @var string
     */
    private $url;

    /**
     * The function associated to the current url.
     *
     * @var object
     */
    private $function;

    /**
     * The controller name.
     *
     * @var string
     */
    private $controller;

    /**
     * The controller method name.
     *
     * @var string
     */
    private $method;

    const HEADER_404 = 'HTTP/1.0 404 Not Found';


    public function __construct()
    {
        $this->url = $this->getUrl();
        $this->function = Route::getFunc($this->url);
        $this->controller = Str::before($this->url, '/');
        $this->method = Str::after($this->url, '/');
    }


    /**
     * Start the loading of the page
     */
    public function load()
    {
        if (!Maintenance::hasAccess()) {
            Maintenance::call();
        }

        $this->initialize();

        if ($this->exists()) {
            Middleware::loadBefore();
            $this->loadPage();
            Middleware::loadAfter();
        } else {
            self::load404();
        }
    }


    /**
     * Load the requested page
     *
     * @return  mixed  the method return value
     */
    private function loadPage()
    {
        //Append the current route closure to a new controller and call it
        if (isset($this->function)) {
            return Controller::closure($this->function);
        }

        //Call controller's index
        if (Controller::exists($this->url)) {
            return Controller::call($this->url);
        }

        //Call controller's method
        if (Controller::methodExists($this->controller, $this->method)) {
            return Controller::method($this->controller, $this->method);
        }
    }


    /**
     * Returns true if the current route exists, false otherwise
     *
     * @return  bool  true if the current route exists, false otherwise
     */
    private function exists()
    {
        return !Route::isBlocked($this->url) &&
            (isset($this->function) ||
            Controller::exists($this->url) ||
            Controller::methodExists($this->controller, $this->method));
    }


    /**
     * Initialize the core classes
     */
    private function initialize()
    {
        DB::initialize();
        Cache::initialize();
    }


    /**
     * Returns the current url processed
     *
     * @return  string  the current url processed
     */
    private function getUrl()
    {
        $url = Request::hasGet('url') ?
            Request::get('url') : getMainPage();
        $url = Str::sanitizeUrl($url);

        return Route::getRedirection($url) ?? $url;
    }


    /**
     * Load the 404 page
     * Warning: This method stops the current script
     */
    public function load404()
    {
        header(self::HEADER_404);
        Controller::call(CORE_CONFIG['controller_404']);
        exit;
    }

}
