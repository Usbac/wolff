<?php

require('../vendor/autoload.php');

$config = require('../system/config.php');

$wolff = new Wolff\Kernel($config);
$wolff->start();
