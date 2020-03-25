<?php

define('CONFIG', [
    //Server
    'dbms'        => 'mysql',
    'server'      => 'localhost',
    'db'          => '',
    'db_username' => '',
    'db_password' => '',

    //Directories
    'root_dir'   => dirname(__DIR__),
    'system_dir' => 'system',
    'app_dir'    => 'app',
    'cache_dir'  => 'cache',
    'public_dir' => 'public',

    //Environment
    'env_file'     => 'system/.env',
    'env_override' => true,

    //General
    'title'     => 'Wolff',
    'language'  => 'english',

    //Extra
    'log_on'         => true,
    'development_on' => true,
    'template_on'    => true,
    'cache_on'       => true,
    'maintenance_on' => false
]);
