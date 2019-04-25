<?php

namespace Controller;

use Core\Controller;

Class _Maintenance extends Controller
{

    public function index()
    {
        $this->data['lang'] = $this->load->language('maintenance');
        $this->load->view('maintenance', $this->data);
    }

}