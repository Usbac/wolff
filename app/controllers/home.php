<?php

namespace Controller;

use Wolff\Core\Language;
use Wolff\Core\View;

class Home extends \Wolff\Core\Controller
{
    public function index($req, $res)
    {
        $data['lang'] = Language::get('home');
        View::render('home', $data);
    }
}
