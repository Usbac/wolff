<?php

namespace Definitions;

use Core\Route;

/**
 * Use this file for declaring routes, blocks, redirections and more...
 */

Route::add('main_page', function () {
    $this->load->controller('home');
});
