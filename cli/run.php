<?php

chdir(dirname(__FILE__));

require('..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
include('..' . DIRECTORY_SEPARATOR . 'config.php');
include('..' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'std-library.php');
include('..' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'Routes.php');

$wolffie = new Cli\Wolffie();

echo "\n \n ---> WELCOME TO WOLFFIE <--- \n \n";
while (true) {
    $wolffie->mainMenu();
}