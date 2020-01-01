<?php

namespace Definitions;

use Core\{Route, Controller};

/**
 * Use this file for declaring routes, blocks, redirections and more...
 */

Route::get('main_page', function () {
    Controller::call('home');
});
