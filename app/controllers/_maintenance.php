<?php

namespace Controller;

use Core\{Controller, View};

Class _Maintenance extends Controller
{

    public function index()
    {
        $data['lang'] = $this->load->language('maintenance');
        View::render('maintenance', $data);
    }

}
