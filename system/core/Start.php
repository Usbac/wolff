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
     * The controller name and its method name separated by a @,
     * used to call it.
     *
     * @var string
     */
    private $method;

    const HEADER_404 = 'HTTP/1.0 404 Not Found';


    /**
     * Start the loading of the page
     */
    public function load()
    {
        $this->url = $this->getUrl();
        $this->function = Route::getFunc($this->url);
        $this->method = Str::before($this->url, '/') . '@' . Str::after($this->url, '/');

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
     * Returns true if the current route exists, false otherwise
     *
     * @return  bool  true if the current route exists, false otherwise
     */
    private function exists()
    {
        return !Route::isBlocked($this->url) &&
            (isset($this->function) ||
            Controller::exists($this->url) ||
            Controller::methodExists($this->method));
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
     * Load the requested page
     */
    private function loadPage()
    {
        if (isset($this->function)) {
            Controller::closure($this->function);
        } elseif (Controller::exists($this->url)) {
            Controller::call($this->url);
        } elseif (Controller::methodExists($this->method)) {
            Controller::method($this->method);
        }
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
