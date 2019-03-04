<?php

use core\Controller;
require_once 'Controller.php';

class SecondController extends Controller
{

    public function index()
    {
        return $this->view('Second');
    }

}