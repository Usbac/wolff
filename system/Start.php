<?php

namespace System;

use Core\{Cache, DB, Extension, Loader, Route, Session, Request, Template};
use Utilities\{Maintenance, Upload, Str};

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

    /**
     * File uploader utility.
     *
     * @var Utilities\Upload
     */
    public $upload;


    public function __construct()
    {
        DB::initialize();
        Cache::initialize();
        
        $this->template = new Template();
        $this->session = new Session();

        if (class_exists('Utilities\Upload')) {
            $this->upload = new Upload();
        }

        $this->load = new Loader($this->template, $this->session, $this->upload);
    }


    /**
     * Start the loading of the page
     */
    public function load()
    {
        //Check maintenance mode
        if (Maintenance::isEnabled() && !Maintenance::isClientAllowed()) {
            $this->load->maintenance();
        }

        $url = Str::sanitizeURL(Request::get('url') ?? getMainPage());

        //Load extensions of type before
        if (Extension::isEnabled()) {
            Extension::load('before', $this->load);
        }

        //Check blocked route
        if (Route::isBlocked($url)) {
            $this->load->redirect404();
        }

        $function = Route::get($url);

        if (isset($function)) {
            $function->call($this);
        } elseif (controllerExists($url) || functionExists($url)) {
            $this->load->controller($url);
        } else {
            $this->load->redirect404();
        }

        //Load extensions of type after
        if (Extension::isEnabled()) {
            Extension::load('after', $this->load);
        }
    }

}