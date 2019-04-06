<?php
session_start();

require('vendor/autoload.php');
include('config.php');
include('system/std-library.php');
include('system/Routes.php');

new System\Start;