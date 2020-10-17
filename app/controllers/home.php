<?php

namespace Controller;

use Wolff\Core\Language;
use Wolff\Core\View;

class Home extends \Wolff\Core\Controller
{

    /**
     * Let's create together the next big thing
     */
    public function index($req, $res)
    {
        View::render('home', [
            'lang' => Language::get('home'),
        ]);
    }
}
