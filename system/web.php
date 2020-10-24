<?php

use Wolff\Core\Language;
use Wolff\Core\Maintenance;
use Wolff\Core\Route;
use Wolff\Core\View;

/**
 * Use this file for declaring routes, middlewares and more...
 */

Route::get('/', [ Controller\Home::class, 'index' ]);

Route::code(404, function () {
    View::render('404', [
        'lang' => Language::get('404'),
    ]);
});

Maintenance::set(function () {
    View::render('maintenance', [
        'lang' => Language::get('maintenance'),
    ]);
});
