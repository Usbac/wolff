<?php

namespace System;

use Core\Route;

Route::add('main_page', function() {
    $this->load->controller('home');
});