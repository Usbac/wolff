<?php

namespace Cli;

use Core;

class Wolffie
{

    private $list;
    private $create;
    private $delete;

    private $command;
    private $args;
    private $route;
    private $extension;
    private $db;
    private $app_dir;
    private $public_dir;


    public function __construct() {
        $this->route = new Core\Route();
        $this->extension = new Core\Extension();
        $this->db = Core\Connection::getInstance(WOLFF_DBMS);

        $root = '../';
        $this->app_dir = $root . WOLFF_APP_DIR;
        $this->public_dir = $root . WOLFF_PUBLIC_DIR;
        $this->list = new Lister($this->route, $this->extension, $this->app_dir, $this->public_dir);
        $this->create = new Create($this->route, $this->extension, $this->app_dir);
        $this->delete = new Delete($this->route, $this->extension, $this->app_dir);
    }


    public function mainMenu() {
        $this->command = readline("command -> ");
        $this->args = explode(' ', $this->command);

        switch ($this->args[0]) {
            case 'ls':
                $this->list->index($this->args);
                break;
            case 'mk':
                $this->create->index($this->args);
                break;
            case 'rm':
                $this->delete->index($this->args);
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
            case 'e':
                die();
                break;
            default:
                echo "\e[1;31m WARNING: Command doesn't exists!\e[0m \n \n";
                break;
        }
    }


    private function help() {
        if (empty($this->args[1])) {
            echo "\nMAIN COMMANDS \n";
            echo "\n\e[32m ls \e[0m                     -> List elements";
            echo "\n\e[32m mk \e[0m                     -> Create elements";
            echo "\n\e[32m rm \e[0m                     -> Remove elements";
            echo "\n\e[32m set [constant] [value] \e[0m -> Set a configuration constant";
            echo "\n\e[32m export [query] \e[0m         -> Export a query to a csv file";
            echo "\n\e[32m help [command] \e[0m         -> Get help";
            echo "\n\e[32m version \e[0m                -> Get the Wolff version";
            echo "\n\e[32m e \e[0m                      -> Escape";
            echo "\n \n\e[1;30m Run help followed by one of the commands showed above for more information.\e[0m \n \n";
            return;
        }

        switch ($this->args[1]) {
            case 'ls':
                echo "\nLIST COMMANDS \n";
                echo "\n\e[32m views \e[0m       -> List the available views.";
                echo "\n\e[32m models \e[0m      -> List the available models.";
                echo "\n\e[32m controllers \e[0m -> List the available controllers.";
                echo "\n\e[32m languages \e[0m   -> List the available languages.";
                echo "\n\e[32m extensions \e[0m  -> List the available extensions.";
                echo "\n\e[32m public \e[0m      -> List all the files in the public folder.";
                echo "\n\e[32m ip \e[0m          -> List all the allowed IPs for maintenance mode.";
                echo "\n\e[32m config \e[0m      -> List the config constants. \n \n";
                break;
            case 'mk':
                echo "\nCREATE COMMANDS";
                echo "\n \nPage related:";
                echo "\n\e[32m page [path] \e[0m                   -> Create a page (view, model and controller).";
                echo "\n\e[32m view [path] \e[0m                   -> Create a view.";
                echo "\n\e[32m model [path] \e[0m                  -> Create a model.";
                echo "\n\e[32m controller [path] \e[0m             -> Create a controller.";
                echo "\n\e[32m library [path] \e[0m                -> Create a library.";
                echo "\n\e[32m language [name] \e[0m               -> Create a language.";
                echo "\n\e[32m extension [name] \e[0m              -> Create a extension.";
                echo "\n\e[32m ip [name] \e[0m                     -> Add an IP to the maintenance mode whitelist.";
                echo "\n \n\e[1;30m If the [path] includes folders that doesn't exists, those folders will be created automatically.\e[0m";

                echo "\n \nRoutes related:";
                echo "\n\e[32m route [url] [controller path] \e[0m -> Create a route.";
                echo "\n\e[32m redirect [orig] [dest] [code] \e[0m -> Create a redirect.";
                echo "\n\e[32m block [route] \e[0m                 -> Block a route.";
                echo "\n \n\e[1;30m These changes will be applied to the routes.php file inside the system folder.\e[0m \n \n";
                break;
            case 'rm':
                echo "\nREMOVE COMMANDS \n";
                echo "\n\e[32m page [path] \e[0m        -> Delete a page (model and controller).";
                echo "\n\e[32m view [path] \e[0m        -> Delete a view.";
                echo "\n\e[32m model [path] \e[0m       -> Delete a model.";
                echo "\n\e[32m controller [path] \e[0m  -> Delete a controller.";
                echo "\n\e[32m library [path] \e[0m     -> Delete a library.";
                echo "\n\e[32m extension [path] \e[0m   -> Delete a extension.";
                echo "\n\e[32m ip [name] \e[0m          -> Remove an IP from the maintenance mode whitelist.";
                echo "\n\e[32m language [name] \e[0m    -> Delete a language.";
                echo "\n\e[32m cache \e[0m              -> Delete all the cache files.";
                echo "\n \n\e[1;30m The file extension must be specified in the [path] only when deleting views.\e[0m \n \n";
                break;
            case 'set':
                echo "\nModify a constant defined in the config.php file. \n";
                echo "Example changing the language to english: set language 'english'. \n \n";
                break;
            case 'help':
                echo "\nIs this recursion? \n \n";
                break;
            case 'version':
                echo "\nShow the current version of Wolff \n \n";
                break;
            case 'e':
                echo "\nEscape from Wolffie \n \n";
                break;
            case 'export':
                echo "\nExport a query result to a .csv file in the project root folder \n \n";
                break;
            default:
                echo "\e[1;31m WARNING: Command doesn't exists!\e[0m \n \n";
                break;
        }
    }


    private function export() {
        $sql = substr($this->command, strlen($this->args[1]) + 1);

        if (!$query = $this->db->query($sql)) {
            echo "WARNING: Error in query \n \n";
            return;
        }

        $result = [];
        while ($row = $query->fetch_assoc()) {
            $result[] = $row;
        }

        @arrayToCsv('sql_' . date('y-m-d'), $result);
        echo "\n Query exported successfully! \n \n";
    }


    private function set() {
        $file = '../config.php';
        $original = "/define\((\s){0,}?[\'\"]" . strtoupper($this->args[1]) . "[\'\"](\s){0,}?,(.*?)\)\;/";
        $replacement = "define('" . strtoupper($this->args[1]) . "', " . $this->args[2] . ");";


        if (!$content = file_get_contents($file)) {
            echo "\e[1;31m WARNING: Couldn't read the config file!\e[0m \n \n";
            return;
        }

        if (!preg_match($original, $content)) {
            echo "\e[1;31m WARNING: Constant doesn't exists!\e[0m \n \n";
            return;
        }

        $content = preg_replace($original, $replacement, $content);
        file_put_contents($file, $content);

        echo "Constant " . $this->args[1] . " modified successfully! \n \n";
    }


    private function version() {
        $data = json_decode(file_get_contents('../composer.json'), true);
        echo "WOLFF v" . $data['version'] . "\n \n";
    }

}