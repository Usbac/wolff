<?php

require '../vendor/autoload.php';
require '../system/web.php';

$config = require '../system/config.php';

$wolff = new Wolff\Kernel($config);
$wolff->start();
