<?php

return [
    //Database
    'db' => [
        'dbms'     => 'mysql',
        'server'   => 'localhost',
        'name'     => '',
        'username' => '',
        'password' => ''
    ],

    //Environment
    'env_file'     => 'system/.env.example',
    'env_override' => true,

    //General
    'language' => 'english',

    //Extra
    'log_on'         => true,
    'development_on' => true,
    'template_on'    => true,
    'cache_on'       => true,
    'stdlib_on'      => true,
    'maintenance_on' => false
];
