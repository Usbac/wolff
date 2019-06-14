<?php

namespace Cli;

use Core\DB;

class Wolffie
{

    private $argv;

    const CONFIG_FILE = 'system/config.php';
    const DATE_FORMAT = 'y-m-d';


    public function __construct($argv)
    {
        $this->argv = $argv;

        if (!isset($this->argv[1])) {
            echo "\e[1;31m WARNING: No command specified!\e[0m\n";

            return;
        }

        $this->mainMenu();
    }


    public function mainMenu()
    {
        switch ($this->argv[1]) {
            case 'ls':
                new Lister($this->argv);
                break;
            case 'mk':
                new Create($this->argv);
                break;
            case 'rm':
                new Delete($this->argv);
                break;
            case 'set':
                $this->set();
                break;
            case 'help':
                $this->help();
                break;
            case 'version':
                $this->version();
                break;
            case 'export':
                $this->export();
                break;
            default:
                echo "\e[1;31m WARNING: Command doesn't exists!\e[0m\n";
                break;
        }
    }


    private function help()
    {
        if (empty($this->argv[2])) {
            echo "\nMAIN COMMANDS \n";
            echo "\n\e[32m ls \e[0m                     -> List elements";
            echo "\n\e[32m mk \e[0m                     -> Create elements";
            echo "\n\e[32m rm \e[0m                     -> Remove elements";
            echo "\n\e[32m set [constant] [value] \e[0m -> Set a configuration constant";
            echo "\n\e[32m export [query] \e[0m         -> Export a query to a csv file";
            echo "\n\e[32m help [command] \e[0m         -> Get help";
            echo "\n\e[32m version \e[0m                -> Get the Wolff version";
            echo "\n \n\e[1;30m Run help followed by one of the commands showed above for more information.\e[0m\n";

            return;
        }

        switch ($this->argv[2]) {
            case 'ls':
                echo "\nLIST COMMANDS \n";
                echo "\n\e[32m views \e[0m       -> List the available views.";
                echo "\n\e[32m controllers \e[0m -> List the available controllers.";
                echo "\n\e[32m languages \e[0m   -> List the available languages.";
                echo "\n\e[32m extensions \e[0m  -> List the available extensions.";
                echo "\n\e[32m public \e[0m      -> List all the files in the public folder.";
                echo "\n\e[32m ip \e[0m          -> List all the allowed IPs for maintenance mode.";
                echo "\n\e[32m config \e[0m      -> List the config constants.\n";
                break;
            case 'mk':
                echo "\nCREATE COMMANDS";
                echo "\n \nPage related:";
                echo "\n\e[32m page [path] \e[0m                   -> Create a page (view and controller).";
                echo "\n\e[32m view [path] \e[0m                   -> Create a view.";
                echo "\n\e[32m controller [path] \e[0m             -> Create a controller.";
                echo "\n\e[32m language [name] \e[0m               -> Create a language.";
                echo "\n\e[32m extension [name] \e[0m              -> Create a extension.";
                echo "\n\e[32m ip [name] \e[0m                     -> Add an IP to the maintenance mode whitelist.";
                echo "\n \n\e[1;30m If the [path] includes folders that doesn't exists, those folders will be created automatically.\e[0m";

                echo "\n \nRoutes related:";
                echo "\n\e[32m route [url] [controller path] \e[0m -> Create a route.";
                echo "\n\e[32m redirect [orig] [dest] [code] \e[0m -> Create a redirect.";
                echo "\n\e[32m block [route] \e[0m                 -> Block a route.";
                echo "\n \n\e[1;30m These changes will be applied to the routes.php file inside the system folder.\e[0m\n";
                break;
            case 'rm':
                echo "\nREMOVE COMMANDS \n";
                echo "\n\e[32m view [path] \e[0m        -> Delete a view.";
                echo "\n\e[32m controller [path] \e[0m  -> Delete a controller.";
                echo "\n\e[32m extension [path] \e[0m   -> Delete a extension.";
                echo "\n\e[32m ip [name] \e[0m          -> Remove an IP from the maintenance mode whitelist.";
                echo "\n\e[32m language [name] \e[0m    -> Delete a language.";
                echo "\n\e[32m cache \e[0m              -> Delete all the cache files.";
                echo "\n \n\e[1;30m The file extension must be specified in the [path] only when deleting views.\e[0m\n";
                break;
            case 'set':
                echo "\nModify a constant defined in the config.php file. \n";
                echo "Example changing the language to english:\e[32m php wolffie set language 'english'\e[0m\n";
                break;
            case 'help':
                echo "\nIs this recursion?\n";
                break;
            case 'version':
                echo "\nShow the current version of Wolff\n";
                break;
            case 'export':
                echo "\nExport a query result to a .csv file in the project root folder\n";
                break;
            default:
                echo "\e[1;31m WARNING: Command doesn't exists!\e[0m\n";
                break;
        }
    }


    private function export()
    {
        DB::initialize();
        if (!$query = DB::run($this->argv[2])->rows) {
            echo "\e[1;31m WARNING: Error in query\e[0m\n";

            return;
        }

        @arrayToCsv('sql_' . date(self::DATE_FORMAT), $query);
        echo "\n Query exported successfully!\n";
    }


    private function set()
    {
        $original = "/[\'\"]" . $this->argv[2] . "[\'\"](\s){0,}?=>(\s){0,}?(.*)?/";
        $replacement = "'" . $this->argv[2] . "' => " . $this->argv[3] . ",";

        if (!$content = file_get_contents(self::CONFIG_FILE)) {
            echo "\e[1;31m WARNING: Couldn't read the config file!\e[0m\n";

            return;
        }

        if (!preg_match($original, $content)) {
            echo "\e[1;31m WARNING: Constant doesn't exists!\e[0m\n";

            return;
        }

        $content = preg_replace($original, $replacement, $content);
        file_put_contents(self::CONFIG_FILE, $content);

        echo "Constant " . $this->argv[2] . " modified successfully!\n";
    }


    private function version()
    {
        echo "\e[32m WOLFF v" . wolffVersion() . "\e[0m\n";
    }

}
