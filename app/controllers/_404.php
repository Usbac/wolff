<?php

namespace Controller;

Class _404 extends \Core\Controller
{

    public function index() {
        $this->data['lang'] = $this->load->language('404');
        $this->load->view('404', $this->data);
    }

}