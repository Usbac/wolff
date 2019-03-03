<?php
session_start();

spl_autoload_register(function($name) {
    require($name . '.php');
});

include('config.php');
include('system/routes.php');

$start = new Start();