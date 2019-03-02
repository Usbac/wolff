<?php

class Controller_home extends Controller {

    public function index() {
        $data['lang'] = $this->load->language('home');
        $this->load->view('home', $data);
    }


    public function sayHello() {
        $this->load->library('home');
        $this->home->helloWorld();
    }

}