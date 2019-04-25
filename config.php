<?php

//Server 
define('WOLFF_DBMS', 'mysql');
define('WOLFF_SERVER', 'localhost');
define('WOLFF_DB', '');
define('WOLFF_DBUSERNAME', '');
define('WOLFF_DBPASSWORD', '');

//Directories
define('WOLFF_ROOT_DIR', dirname($_SERVER['SCRIPT_NAME']) . '/');
define('WOLFF_APP_DIR', WOLFF_ROOT_DIR . 'app/');
define('WOLFF_PUBLIC_DIR', WOLFF_ROOT_DIR . 'public/');
define('WOLFF_EXTENSION_DIR', WOLFF_ROOT_DIR . 'system/Extensions/');
define('WOLFF_CACHE_DIR', WOLFF_ROOT_DIR . 'cache/');

//General
define('WOLFF_VERSION', '0.9.7.4');
define('WOLFF_START', microtime(true));
define('WOLFF_PAGE_TITLE', 'Wolff');
define('WOLFF_MAIN_PAGE', 'main_page');
define('WOLFF_LANGUAGE', 'english');

//Extra
define('WOLFF_CACHE_ON', true);
define('WOLFF_EXTENSIONS_ON', true);
define('WOLFF_MAINTENANCE_ON', false);