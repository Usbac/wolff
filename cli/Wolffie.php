<?php

namespace Cli;

use System as Sys;
use Library as Lib;
use Core;

class Wolffie {

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
        
        $root = '..' . DIRECTORY_SEPARATOR;
        $this->app_dir = $root . WOLFF_APP_DIR;
        $this->public_dir = $root . WOLFF_PUBLIC_DIR;
        $this->list = new Lister($this->route, $this->extension, $this->app_dir, $this->public_dir);
        $this->create = new Create($this->route, $this->extension, $this->app_dir);
        $this->delete = new Delete($this->route, $this->extension, $this->app_dir);
    }


    public function mainMenu() {
        $this->command = readline("command -> ");
        $this->args = explode(' ', $this->command);

        switch($this->args[0]) {
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
                echo "WARNING: Command doesn't exists \n \n";
                break;
        }
    }


    private function help() {
        if (empty($this->args[1])) {
            echo "\nMAIN COMMANDS \n";
            echo "\n ls                      -> List elements";
            echo "\n mk                      -> Create elements";
            echo "\n rm                      -> Remove elements";
            echo "\n set [constant] [value]  -> Set a configuration constant";
            echo "\n export [query]          -> Export a query to a csv file";
            echo "\n help [command]          -> Get help";
            echo "\n version                 -> Get the Wolff version";
            echo "\n e                       -> Escape";
            echo "\n \n*Run help followed by one of the commands showed above for more information. \n \n";
            return;
        }

        switch($this->args[1]) {
            case 'ls':
                echo "\nLIST COMMANDS \n";
                echo "\n views       -> List the available views.";
                echo "\n models      -> List the available models.";
                echo "\n controllers -> List the available controllers.";
                echo "\n languages   -> List the available languages.";
                echo "\n extensions  -> List the available extensions.";
                echo "\n public      -> List all the files in the public folder.";
                echo "\n ip          -> List all the allowed IPs for maintenance mode.";
                echo "\n config      -> List the config constants. \n \n";
                break;
            case 'mk':
                echo "\nCREATE COMMANDS";
                echo "\n \nPage related:";
                echo "\n page [path]                   -> Create a page (view, model and controller).";
                echo "\n view [path]                   -> Create a view.";
                echo "\n model [path]                  -> Create a model.";
                echo "\n controller [path]             -> Create a controller.";
                echo "\n library [path]                -> Create a library.";
                echo "\n language [name]               -> Create a language.";
                echo "\n extension [name]              -> Create a extension.";
                echo "\n ip [name]                     -> Add an IP to the maintenance mode whitelist.";
                echo "\n \n*If the [path] includes folders that doesn't exists, those folders will be created automatically.";

                echo "\n \nRoutes related:";
                echo "\n route [url] [controller path] -> Create a route.";
                echo "\n redirect [orig] [dest] [code] -> Create a redirect.";
                echo "\n block [route]                 -> Block a route.";
                echo "\n \n*These changes will be applied to the routes.php file inside the system folder. \n \n";
                break;
            case 'rm':
                echo "\nREMOVE COMMANDS \n";
                echo "\n page [path]        -> Delete a page (model and controller).";
                echo "\n view [path]        -> Delete a view.";
                echo "\n model [path]       -> Delete a model.";
                echo "\n controller [path]  -> Delete a controller.";
                echo "\n library [path]     -> Delete a library.";
                echo "\n extension [path]   -> Delete a extension.";
                echo "\n ip [name]          -> Remove an IP from the maintenance mode whitelist.";
                echo "\n language [name]    -> Delete a language.";
                echo "\n cache              -> Delete all the cache files.";
                echo "\n \n*The file extension must be specified in the [path] only when deleting views. \n \n";
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
            default:
                echo "WARNING: Command doesn't exists \n \n";
                break;
        }
    }

    
    private function export() {
        $sql = substr($this->command, strlen($this->args[1])+1);

        if (!$query = $this->db->query($sql)) {
            echo "WARNING: Error in query \n \n";
            return;
        }

        $result = [];
        while ($row = $query->fetch_assoc()) {
            $result[] = $row;
        }

        @arrayToCsv($result, 'sql_' . date('y-m-d'));
        echo "\n Query exported successfully! \n \n";
    }


    private function set() {
        $file = '../config.php';
        $original = "/define\((\s){0,}?[\'\"]" . strtoupper($this->args[1]) . "[\'\"](\s){0,}?,(.*?)\)\;/";
        $replacement = "define('" . strtoupper($this->args[1]) . "', " . $this->args[2] . ");";
        

        if (!$content = file_get_contents($file)) {
            echo "WARNING: Couldn't read the config file \n \n";
            return;
        }

        if (!preg_match($original, $content)) {
            echo "WARNING: Constant doesn't exists \n \n";
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