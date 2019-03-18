<?php
session_start();

require('vendor/autoload.php');
include('config.php');
include('system/Routes.php');

new Root\Start;