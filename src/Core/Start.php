<?php

namespace Wolff\Core;

use Wolff\Utils\Str;

class Start
{

    const HEADER_404 = 'HTTP/1.0 404 Not Found';

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

        Cache::init();

        if ($this->exists()) {
            $req = $this->getRequest();
            Middleware::loadBefore();
            $this->loadPage($req);
            Middleware::loadAfter();
            Route::execCode();
        } else {
            header(self::HEADER_404);
            Route::execCode();
        }
    }


    /**
     * Returns a new request object
     *
     * @return  Http\Request  The new request object
     */
    private function getRequest()
    {
        return new Http\Request(
            $_GET,
            $_POST,
            $_FILES,
            $_SERVER
        );
    }


    /**
     * Load the requested page
     *
     * @return  mixed  the method return value
     */
    private function loadPage(Http\Request $req)
    {
        //Append the current route closure to a new controller and call it
        if (isset($this->function)) {
            if ($this->function instanceof \Closure) {
                return ($this->function)($req);
            }

            $path = explode('@', $this->function);
            return Controller::method($path[0], $path[1] ?? 'index', [ $req ]);
        }

        //Call controller's index
        if (Controller::exists($this->url)) {
            return Controller::method($this->url, 'index', [ $req ]);
        }

        //Call controller's method
        if (Controller::methodExists($this->controller, $this->method)) {
            return Controller::method($this->controller, $this->method, [ $req ]);
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
     * Returns the current url processed
     *
     * @return  string  the current url processed
     */
    private function getUrl()
    {
        $url = isset($_GET['url']) ?
            Str::sanitizeUrl($_GET['url']) :
            (CONFIG['main_page'] ?? '');

        return Route::getRedirection($url) ?? $url;
    }

}
