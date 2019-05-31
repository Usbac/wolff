<?php

namespace Controller;

use Core\Controller;

Class _404 extends Controller
{

    public function index()
    {
        $this->data['lang'] = $this->load->language('404');
        $this->load->view('404', $this->data);
    }

}
