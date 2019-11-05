<?php

namespace Controller;

use Core\{Controller, Language, View};

Class _Maintenance extends Controller
{

    public function index()
    {
        $data['lang'] = Language::get('maintenance');
        View::render('maintenance', $data);
    }

}
