<?php

namespace Controller;

Class home extends \Core\Controller {

    public function index() {
        $this->data['lang'] = $this->load->language('home');
        $model = $this->load->model('home');
        
        $this->load->view('home', $this->data);
    }


    public function sayHello() {
        $libraryHome = $this->load->library('home');
        $libraryHome->helloWorld();
    }

}