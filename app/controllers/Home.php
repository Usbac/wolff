<?php

namespace Controller;

use Wolff\Core\Language;
use Wolff\Core\View;

class Home extends \Wolff\Core\Controller
{

    public function index($req, $res)
    {
        $view = new View();
        $lang = new Language();
        $view->render('home', [
            'lang' => $lang->get('home'),
        ]);
    }
}
