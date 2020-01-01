<?php

namespace Controller;

use Core\{Controller, Language, View};

class Home extends Controller
{

    public function index()
    {
        $data['lang'] = Language::get('home');
        View::render('home', $data);
    }


    public function sayHello()
    {
        echo 'Hello world';
    }

}
