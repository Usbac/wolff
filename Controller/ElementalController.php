<?php

use core\Controller;
require_once 'Controller.php';

class ElementalController extends Controller
{

    public function notFound()
    {
        return $this->view('Errors/404');
    }

}