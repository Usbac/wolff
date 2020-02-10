<?php

define('CONFIG', [
    //Server
    'dbms'        => 'mysql',
    'server'      => 'localhost',
    'db'          => '',
    'db_username' => '',
    'db_password' => '',

    //Directories
    'root_dir'   => $root = dirname(__DIR__) . '/',
    'system_dir' => $root . 'system/',
    'app_dir'    => $root . 'app/',
    'cache_dir'  => $root . 'cache/',
    'public_dir' => $root . 'public/',

    //General
    'title'     => 'Wolff',
    'main_page' => 'main_page',
    'language'  => 'english',

    //Extra
    'db_on'          => true,
    'log_on'         => true,
    'development_on' => true,
    'middlewares_on' => true,
    'template_on'    => true,
    'cache_on'       => true,
    'maintenance_on' => false
]);
