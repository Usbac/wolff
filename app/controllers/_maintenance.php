<?php

namespace Controller;

Class _Maintenance extends \Core\Controller {

    public function index() {
        $this->data['lang'] = $this->load->language('maintenance');
        $this->load->view('maintenance', $this->data);
    }

}