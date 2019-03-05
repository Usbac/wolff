<?php

namespace Controller;

Class home extends \System\Controller {

    public function index() {
        $data['lang'] = $this->load->language('home');
        $model = $this->load->model('home');

        $this->load->view('home', $data);
    }


    public function sayHello() {
        $libraryHome = $this->load->library('home');
        $libraryHome->helloWorld();
    }

}