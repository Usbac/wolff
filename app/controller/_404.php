<?php

namespace App\Controller;

Class _404 extends \System\Controller {

    public function index() {
        $data['lang'] = $this->load->language('404');

        $this->load->view('404', $data);
    }

}