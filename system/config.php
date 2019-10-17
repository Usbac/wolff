<?php

define('CORE_CONFIG', [
    'version'      => '1.9',
    'start'        => microtime(true),
    'views_format' => '.wlf',
    
    'views_folder'     => 'views',
    'languages_folder' => 'languages',

    'maintenance_controller' => '_maintenance',
    '404_controller'         => '_404',
]);
