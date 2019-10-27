<?php

namespace Controller;

use Core\{Controller, View};

Class _404 extends Controller
{

    public function index()
    {
        $data['lang'] = $this->load->language('404');
        View::render('404', $data);
    }

}
