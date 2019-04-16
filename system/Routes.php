<?php

namespace System;

use Core\Route;

/**
 * Use this file for declaring routes, blocks, redirections...
 *
 */

Route::add('main_page', function () {
    $this->load->controller('home');
});