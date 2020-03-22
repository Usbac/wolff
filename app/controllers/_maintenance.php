<?php

namespace Controller;

use Wolff\Core\{Controller, Language, View};

class _Maintenance extends Controller
{

    public function index()
    {
        $data['lang'] = Language::get('maintenance');
        View::render('maintenance', $data);
    }

}
