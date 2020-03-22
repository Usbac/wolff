<?php

namespace Controller;

use Wolff\Core\{Controller, Language, View};

class Home extends Controller
{

    public function index($request)
    {
        $data['lang'] = Language::get('home');
        View::render('home', $data);
    }

}
