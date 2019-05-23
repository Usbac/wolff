<?php

namespace Core;

class Controller
{

    /**
     * Loader.
     *
     * @var Core\Loader
     */
    protected $load;

    /**
     * Session manager.
     *
     * @var Core\Session
     */
    protected $session;

    /**
     * General data of the controller.
     *
     * @var array
     */
    protected $data;

    /**
     * File uploader utility.
     *
     * @var Utilities\Upload
     */
    protected $upload;


    public function __construct()
    {
        $this->data = [];
    }


    /**
     * Set the loader
     *
     * @param  Loader  $load  the loader
     */
    public function setLoader(Loader $load)
    {
        $this->load = &$load;
    }


    /**
     * Set the session
     *
     * @param  Session  $session  the session
     */
    public function setSession(Session $session)
    {
        $this->session = &$session;
    }


    /**
     * Add an utility
     *
     * @param  string  $key  the classname to refer to in the controller
     * @param  string  $class  the classname
     */
    public function addUtility($key, $class)
    {
        $this->$key = Factory::utility($class);
    }

}
