<?php

namespace Core;

use Utilities\Str;

class Start
{

    /**
     * Loader.
     *
     * @var Core\Loader
     */
    public $load;


    public function __construct()
    {
        $this->load = Factory::loader();
    }


    /**
     * Start the loading of the page
     */
    public function load()
    {
        $url = $this->getUrl();

        $this->validateAccess($url);
        $this->initialize();

        Extension::loadBefore();

        $this->loadPage($url);

        Extension::loadAfter();
    }


    /**
     * Initialize some of the core classes
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
        $url = Request::hasGet('url') ? Request::get('url') : getMainPage();
        $url = Str::sanitizeUrl($url);

        return Route::getRedirection($url) ?? $url;
    }


    /**
     * Load the requested page
     * This can redirect to the 404 page
     *
     * @param  string  $url the page url
     */
    private function loadPage(string $url)
    {
        $function = Route::getFunc($url);

        if (isset($function)) {
            $this->load->closure($function);
        } elseif (controllerExists($url) || functionExists($url)) {
            $this->load->controller($url);
        } else {
            $this->load->redirect404();
        }
    }


    /**
     * Validate the client access to the page
     * This can redirect to the maintenance or the 404 page
     *
     * @param  string  $url the page url
     */
    private function validateAccess($url)
    {
        //Check maintenance mode
        if (!Maintenance::hasAccess()) {
            $this->load->maintenance();
        }

        //Check blocked route
        if (Route::isBlocked($url)) {
            $this->load->redirect404();
        }
    }

}
