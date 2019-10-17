<?php

namespace Controller;

use Core\{Controller, View};

class Home extends Controller
{

    public function index()
    {
        $this->data['lang'] = $this->load->language('home');
        View::render('home', $this->data);
    }


    public function sayHello()
    {
        echo 'Hello world';
    }

}
