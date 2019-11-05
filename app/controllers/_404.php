<?php

namespace Controller;

use Core\{Controller, Language, View};

Class _404 extends Controller
{

    public function index()
    {
        $data['lang'] = Language::get('404');
        View::render('404', $data);
    }

}
