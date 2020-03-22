<?php

namespace Wolff\Core;

use Wolff\Utils\Str;

class Kernel
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


    /**
     * Default constructor
     */
    public function __construct()
    {
        Config::init();
        Cache::init();

        $this->url = $this->getUrl();
        $this->function = Route::getVal($this->url);

        if (is_string($this->function)) {
            $path = explode('@', $this->function);
            $this->controller = $path[0];
            $this->method = empty($path[1]) ? 'index' : $path[1];
        } else {
            $this->controller = substr($this->url, 0, strpos($this->url, '/'));
            $this->method = substr($this->url, strpos($this->url, '/') + 1);
        }
    }


    /**
     * Starts the loading of the page
     */
    public function start()
    {
        if (CONFIG['maintenance_on'] &&
            !Maintenance::hasAccess()) {
            Maintenance::call();
        }

        if (!$this->isAccessible()) {
            http_response_code(404);
            return;
        }

        $this->load();
        Route::execCode();
    }


    /**
     * Loads the current route and its middlewares
     */
    private function load()
    {
        $req = $this->getRequest();
        Middleware::loadBefore($this->url, $req);
        $this->loadPage($req);
        Middleware::loadAfter($this->url, $req);
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
     * Loads the requested page
     */
    private function loadPage(Http\Request $req)
    {
        if ($this->function instanceof \Closure) {
            ($this->function)($req);
        } else if (Controller::hasMethod($this->controller, $this->method)) {
            Controller::method($this->controller, $this->method, [ $req ]);
        } else if (Controller::exists($this->url)) {
            Controller::method($this->url, 'index', [ $req ]);
        }
    }


    /**
     * Returns true if the current route is accessible,
     * false otherwise
     *
     * @return bool true if the current route is accessible,
     * false otherwise
     */
    private function isAccessible()
    {
        return (!Route::isBlocked($this->url) &&
            ($this->function instanceof \Closure ||
            Controller::hasMethod($this->controller, $this->method) ||
            Controller::exists($this->url)));
    }


    /**
     * Returns the current url processed
     *
     * @return  string  the current url processed
     */
    private function getUrl()
    {
        $url = $_SERVER['REQUEST_URI'];

        //Remove possible project folder from url
        if (strpos(CONFIG['root_dir'], $_SERVER['DOCUMENT_ROOT']) === 0) {
            $project_dir = substr(CONFIG['root_dir'], strlen($_SERVER['DOCUMENT_ROOT']));
            $url = substr($url, strlen($project_dir));
        }

        $url = ltrim($url, '/');

        //Remove parameters
        if (($query_index = strpos($url, '?')) !== false) {
            $url = substr($url, 0, $query_index);
        }

        $url = Str::sanitizeUrl($url);

        //Redirection
        $redirect = Route::getRedirection($url);
        if (isset($redirect)) {
            http_response_code($redirect['code']);
            return $redirect['destiny'];
        }

        return $url;
    }

}
