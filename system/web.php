<?php

use \Wolff\Core\{Controller, Route};

/**
 * Use this file for declaring routes, middlewares and more...
 */

Route::get('/', 'home@index');

Route::code('404', function() {
    Controller::method('_404');
});
