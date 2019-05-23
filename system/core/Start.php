<?php

namespace Core;

use Utilities\Str;

class Start
{

    /**
     * Extension.
     *
     * @var Core\Extension
     */
    public $extension;

    /**
     * Loader.
     *
     * @var Core\Loader
     */
    public $load;

    /**
     * Template manager.
     *
     * @var Core\Template
     */
    public $template;

    /**
     * Session manager.
     *
     * @var Core\Session
     */
    public $session;


    public function __construct()
    {
        $this->template = new Template();
        $this->session = new Session();
        $this->load = new Loader($this->template, $this->session);
    }


    /**
     * Start the loading of the page
     */
    public function load()
    {
        $url = $this->getUrl();

        $this->checkAccess($url);
        $this->initialize();

        //Load extensions of type before
        if (Extension::isEnabled()) {
            Extension::load(Extension::BEFORE, $this->load);
        }

        $this->loadPage($url);

        //Load extensions of type after
        if (Extension::isEnabled()) {
            Extension::load(Extension::AFTER, $this->load);
        }
    }


    /**
     * Initialize some of the core classes
     */
    public function initialize()
    {
        DB::initialize();
        Cache::initialize();
    }


    /**
     * Returns the current url processed
     *
     * @return  string  the current url processed
     */
    public function getUrl()
    {
        $url = Request::get('url') ?? getMainPage();
        $url = Str::sanitizeURL($url);
        return Route::getRedirection($url) ?? $url;
    }


    /**
     * Load the requested page
     *
     * @param  string  $url the page url
     */
    public function loadPage(string $url)
    {
        $function = Route::get($url);

        if (isset($function)) {
            $this->load->closure($function);
        } elseif (controllerExists($url) || functionExists($url)) {
            $this->load->controller($url);
        } else {
            $this->load->redirect404();
        }
    }


    /**
     * Check the client access to the page
     * This can redirect to the maintenance or the 404 page
     *
     * @param  string  $url the page url
     */
    public function checkAccess($url)
    {
        //Check maintenance mode
        if (Maintenance::isEnabled() && !Maintenance::isClientAllowed()) {
            $this->load->maintenance();
        }

        //Check blocked route
        if (Route::isBlocked($url)) {
            $this->load->redirect404();
        }
    }

}
