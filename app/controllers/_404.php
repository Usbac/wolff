<?php

namespace Controller;

use Wolff\Core\{Controller, Language, View};

class _404 extends Controller
{

    public function index()
    {
        $data['lang'] = Language::get('404');
        View::render('404', $data);
    }

}
