<?php

define('CORE_CONFIG', [
    'version' => '1.10',
    'start'   => microtime(true),

    'views_folder'     => 'views',
    'languages_folder' => 'languages',

    'maintenance_controller' => '_maintenance',
    '404_controller'         => '_404',
]);
