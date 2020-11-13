<?php

require '../vendor/autoload.php';

$config = require '../system/config.php';
if ($config['stdlib_on']) {
    include_once '../vendor/usbac/wolff-framework/src/stdlib.php';
}

require '../system/web.php';

$wolff = new Wolff\Kernel($config);
$wolff->start();
