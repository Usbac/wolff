<?php 
//Wolff v0.9.3

//Server 
define('WOLFF_DBMS', 'mysql'); 
define('WOLFF_SERVER', 'localhost'); 
define('WOLFF_DB', ''); 
define('WOLFF_USERNAME', 'root');
define('WOLFF_DBPASSWORD', ''); 

//General 
define('WOLFF_SYS_DIR', dirname($_SERVER['SCRIPT_NAME']) . DIRECTORY_SEPARATOR);
define('WOLFF_APP_DIR', WOLFF_SYS_DIR . 'app/');
define('WOLFF_PUBLIC_DIR', WOLFF_SYS_DIR . 'public/');
define('WOLFF_PAGE_TITLE', 'Wolff'); 
define('WOLFF_MAIN_PAGE', 'main_page'); 
define('WOLFF_LANGUAGE', 'english');

//Others
define('WOLFF_CACHE_ON', true);
define('WOLFF_EXTENSIONS_ON', true);
define('WOLFF_MAINTENANCE_ON', false);