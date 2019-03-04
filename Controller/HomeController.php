<?php

use core\Controller;
require_once 'Controller.php';

class HomeController extends Controller
{

    public function index()
    {
        echo "TEST";
    }

    public function test()
    {
        return $this->view("Home");
    }

}