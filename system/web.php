<?php

use Wolff\Core\Language;
use Wolff\Core\Maintenance;
use Wolff\Core\Middleware;
use Wolff\Core\Route;
use Wolff\Core\View;

/**
 * Use this file for declaring routes, middlewares and more...
 */

Route::get('/', 'home@index');

Route::code(404, function ($req) {
    $data['lang'] = Language::get('404');
    View::render('404', $data);
});

Maintenance::set(function ($req) {
    $data['lang'] = Language::get('maintenance');
    View::render('maintenance', $data);
});
