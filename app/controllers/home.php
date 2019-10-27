<?php

namespace Controller;

use Core\{Controller, View};

class Home extends Controller
{

    public function index()
    {
        $data['lang'] = $this->load->language('home');
        View::render('home', $data);
    }


    public function sayHello()
    {
        echo 'Hello world';
    }

}
