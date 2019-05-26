<?php

require('../vendor/autoload.php');

if (CONFIG['development_on']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

(new Core\Start)->load();
