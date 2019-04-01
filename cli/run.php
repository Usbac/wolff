<?php 

chdir(dirname(__FILE__));

require('../vendor/autoload.php');
include('../config.php');

$wolffie = new Cli\Wolffie();

echo "\n \n ---> WELCOME TO WOLFFIE <--- \n \n";
while (true) {
    $wolffie->mainMenu();
}