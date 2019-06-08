<?php

define('CONFIG', [
    //Server
    'dbms'        => 'mysql',
    'server'      => 'localhost',
    'db'          => '',
    'db_username' => '',
    'db_password' => '',

    //Directories
    'root_dir'      => $root = dirname(__DIR__) . '/',
    'system_dir'    => $root . 'system/',
    'app_dir'       => $root . 'app/',
    'extension_dir' => $root . 'app/extensions/',
    'cache_dir'     => $root . 'cache/',
    'public_dir'    => $root . 'public/',

    //General
    'version'   => '1.1.0',
    'start'     => microtime(true),
    'title'     => 'Wolff',
    'main_page' => 'main_page',
    'language'  => 'english',

    //Extra
    'development_on' => true,
    'extensions_on'  => true,
    'cache_on'       => true,
    'maintenance_on' => false
]);
