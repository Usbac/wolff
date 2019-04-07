<?php 
//Wolff v0.9.2

//Server 
define('DBMS', 'mysql'); 
define('SERVER', 'localhost'); 
define('DB', ''); 
define('USER', 'root');
define('PASSWORD', ''); 

//General 
define('PROJECT_ROOT', dirname($_SERVER['SCRIPT_NAME']) . DIRECTORY_SEPARATOR);
define('APP', PROJECT_ROOT . 'app/');
define('PUBLIC_DIR', PROJECT_ROOT . 'public/');
define('PAGE_TITLE', 'Wolff'); 
define('MAIN_PAGE', 'main_page'); 
define('LANGUAGE', 'english');
define('EXTENSIONS', true);