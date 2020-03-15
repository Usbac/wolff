<?php

define('CORE_CONFIG', [
    //General
    'version'      => '3.0',
    'start'        => microtime(true),
    'views_format' => 'wlf',

    //Folders
    'views_dir'       => 'views',
    'controllers_dir' => 'controllers',
    'middlewares_dir' => 'middlewares',
    'languages_dir'   => 'languages',

    //Default controllers
    'controller_maintenance' => '_maintenance',
]);
