<?php

class Controller_404 extends Controller {

    public function index() {
        $data['lang'] = $this->load->language('404');

        $this->load->view('404', $data);
    }

}