<?php

namespace Core;

use Utilities\Str;

class Start
{

    const HEADER_404 = 'HTTP/1.0 404 Not Found';


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
        $url = Request::hasGet('url') ?
            Request::get('url') : getMainPage();
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
            Controller::closure($function);
        } elseif (Controller::exists($url)) {
            Controller::call($url);
        } elseif (functionExists($url)) {
            Controller::function(Str::before($url, '@') . '@' . Str::after($url, '@'));
        } else {
            self::load404();
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
            Maintenance::call();
        }

        //Check blocked route
        if (Route::isBlocked($url)) {
            self::load404();
        }
    }


    /**
     * Load the 404 page
     * Warning: This method stops the current script
     */
    public function load404()
    {
        header(self::HEADER_404);
        Controller:call(CORE_CONFIG['404_controller']);
        exit;
    }

}
