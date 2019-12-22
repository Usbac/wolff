<?php

define('CORE_CONFIG', [
    //General
    'version'      => '2.0',
    'start'        => microtime(true),
    'views_format' => 'wlf',

    //Folders
    'views_dir'       => 'views',
    'middlewares_dir' => 'middlewares',
    'languages_dir'   => 'languages',

    //Default controllers
    'controller_maintenance' => '_maintenance',
    'controller_404'         => '_404',
]);
