<?php

namespace Core;

class Library
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
     * General data of the library.
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


    public function __construct($load)
    {
        $this->load = &$load;
        $this->session = $this->load->getSession();
        $this->upload = $this->load->getUpload();
        $this->data = [];
    }

}