<?php

namespace Core;

class Controller {

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
	 * Cache system.
	 *
	 * @var Core\Cache
	 */
    protected $cache;

    /**
	 * File uploader utility.
	 *
	 * @var System\Library\Upload
	 */
    protected $upload;
    

    public function __construct($load) {
        $this->load = &$load;
        $this->session = $this->load->getSession();
        $this->cache = $this->load->getCache();
        $this->upload = $this->load->getUpload();
        $this->data = array();
    }

}