<?php

namespace {

    
    /**
     * Sanitize an url
     * @param string url the url
     * @return string the url sanitized
     */
    function sanitizeURL(string $url = WOLFF_MAIN_PAGE) {
        return filter_var(rtrim(strtolower($url), '/'), FILTER_SANITIZE_URL);
    }


    /**
     * Sanitize a path for only letters, numbers and slashes
     * @param string path the path
     * @return string the path sanitized
     */
    function sanitizePath(string $path) {
        return preg_replace('/[^a-zA-Z0-9_\/]/', '', $path);
    }


    /**
     * Checks if the model exists in the indicated directory
     * @param dir the directory of the model
     * @return boolean true if the model exists, false otherwise 
     */
    function modelExists(string $dir) {
        return file_exists(getModelPath($dir));
    }


    /**
     * Returns the complete path of the model
     * @param dir the directory of the model
     * @return string the complete path of the model
     */
    function getModelPath(string $dir) {
        return getServerRoot() . WOLFF_APP_DIR . 'models/' . $dir . '.php';
    }


    /**
     * Checks if the controller exists in the indicated directory
     * @param dir the directory of the controller
     * @return boolean true if the controller exists, false otherwise 
     */
    function controllerExists(string $dir) {
        return file_exists(getControllerPath($dir));
    }

    
    /**
     * Returns the complete path of the controller
     * @param dir the directory of the controller
     * @return string the complete path of the controller
     */
    function getControllerPath(string $dir) {
        return getServerRoot() . WOLFF_APP_DIR . 'controllers/' . $dir . '.php';
    }


    /**
     * Returns true if the controller's function exists, false otherwise
     * @param dir the directory of the controller
     * @return boolean true if the controller's function exists, false otherwise 
     */
    function functionExists(string $dir) {
        //Remove the function from the url and save the function name
        $lastSlash = strrpos($dir, '/');
        $function = substr($dir, $lastSlash + 1);
        $dir = substr($dir, 0, $lastSlash);

        $class = 'Controller\\' . str_replace('/', '\\', $dir);

        try {
            $class = new \ReflectionClass($class);
            $class->getMethod($function);
        } catch(\Exception $e) {
            error_log($e->getMessage());
            return false;
        }

        return true;
    }

    
    /**
     * Checks if the language file exists in the indicated directory
     * @param dir the directory of the language file
     * @param language the language selected (it will take the default language if no language is specified)
     * @return boolean true if the language file exists, false otherwise 
     */
    function languageExists(string $dir, string $language = WOLFF_LANGUAGE) {
        return file_exists(getLanguagePath($dir, $language));
    }

    
    /**
     * Returns the complete path of the language
     * @param dir the directory of the language
     * @return string the complete path of the language
     */
    function getLanguagePath(string $dir, string $language = WOLFF_LANGUAGE) {
        return getServerRoot() . WOLFF_APP_DIR . 'languages/' . $language . '/' . $dir . '.php';
    }


    /**
     * Checks if the library exists in the indicated directory
     * @param dir the directory of the library
     * @return boolean true if the library exists, false otherwise 
     */
    function libraryExists(string $dir) {
        return file_exists(getLibraryPath($dir)); 
    }


    /**
     * Returns the complete path of the library
     * @param dir the directory of the library
     * @return string the complete path of the library
     */
    function getLibraryPath(string $dir) {
        return getServerRoot() . WOLFF_APP_DIR . 'libraries/' . $dir . '.php';
    }

    
    /**
     * Checks if the view exists in the indicated directory
     * @param dir the directory of the view
     * @return boolean true if the view exists, false otherwise 
     */
    function viewExists(string $dir) {
        return file_exists(getViewPath($dir)); 
    }

    
    /**
     * Returns the complete path of the view
     * @param dir the directory of the view
     * @return string the complete path of the view
     */
    function getViewPath(string $dir) {
        return getServerRoot() . WOLFF_APP_DIR . 'views/' . $dir;
    }


    /**
     * Checks if the substring is present in another string
     * @param str the string
     * @param needle substring you are looking for
     * @return boolean true if the substring is present in the string, false otherwise
     */
    function strContains(string $str, string $needle) {
        return strpos($str, $needle) !== false;
    }


    /**
     * Print a string and then die
     * @param $str the string to print
     */
    function echod($str) {
        echo $str;
        die();
    }

    
    /**
     * Print an array in a nice looking way
     * @param array $array the array to print
     */
    function printr(array $array) {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }


    /**
     * Print an array in a nice looking way and then die
     * @param array $array the array to print
     */
    function printrd(array $array) {
        printr($array);
        die();
    }


    /**
     * Var dump a variable and then die
     * @param $var the variable
     */
    function dumpd($var) {
        var_dump($var);
        die();
    }


    /**
     * Returns the first element of an array, or false if it's empty
     * @param array $array
     * @return mixed The first element of an array, or false if it's empty
     */
    function arrayFirst($array) {
        return array_values($array)[0]?? false;
    }


    /**
     * Returns the current version of Wolff defined in the composer.json file
     * @return string The current version of Wolff defined in the composer.json file
     */
    function wolffVersion() {
        $data = json_decode(file_get_contents('composer.json'), true);
        return $data['version'];
    }


    /**
     * Convert an array content into a csv file and download it
     * @param string $filename the desired filename without extension
     * @param array $array the array
     */
    function arrayToCsv(string $filename, array $array) {
        $filename .= ".csv";
        $file = fopen($filename, 'w');

        //Single array
        if (count($array) == count($array, COUNT_RECURSIVE)) {
            fputcsv($file, array_keys($array));
            fputcsv($file, $array);
        //Multidimensional array
        } else {
            fputcsv($file, array_keys(arrayFirst($array)));
            foreach ($array as $row) {
                fputcsv($file, $row);
            }
        }
        
        fclose($file);

        header('Content-Description: File Transfer'); 
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . basename($filename));
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
    }


    /**
     * Returns the current client IP
     * @return string the current client IP
     */
    function getClientIP() {
        $http_client_ip = filter_var($_SERVER['HTTP_CLIENT_IP']?? "", FILTER_VALIDATE_IP);
        $http_forwarded = filter_var($_SERVER['HTTP_X_FORWARDED_FOR']?? "", FILTER_VALIDATE_IP);

        if (!empty($http_client_ip)) {
            return $http_client_ip;
        } 
        
        if (!empty($http_forwarded)) {
            return $http_forwarded;
        }
        
        return $_SERVER['REMOTE_ADDR'];
    }


    /**
     * Returns the server root directory
     * @return string the server root directory
     */
    function getServerRoot() {
        return $_SERVER['DOCUMENT_ROOT'];
    }
    
    
    /**
     *  ---> CONSTANTS <---
     */


    /**
     * Returns true if the extensions are enabled, false otherwise
     * @return bool true if the extensions are enabled, false otherwise
     */
    function extensionsEnabled() {
        return WOLFF_EXTENSIONS_ON;
    }


    /**
     * Returns true if the cache is enabled, false otherwise
     * @return bool true if the cache is enabled, false otherwise
     */
    function cacheEnabled() {
        return WOLFF_CACHE_ON;
    }

    
    /**
     * Returns true if the maintenance mode is enabled, false otherwise
     * @return bool true if the maintenance mode is enabled, false otherwise
     */
    function maintenanceEnabled() {
        return WOLFF_MAINTENANCE_ON;
    }


    /**
     * Returns the language currently used by the project
     * @return string the language name
     */
    function getLanguage() {
        return WOLFF_LANGUAGE;
    }


    /**
     * Returns the root directory of the project
     * @return string the root directory of the project
     */
    function getDirectory() {
        return WOLFF_SYS_DIR;
    }


    /**
     * Returns the app directory of the project
     * @return string the app directory of the project
     */
    function getAppDirectory() {
        return WOLFF_APP_DIR;
    }


    /**
     * Returns the public directory of the project
     * @return string the public directory of the project
     */
    function getPublicDirectory() {
        return WOLFF_PUBLIC_DIR;
    }


    /**
     * Returns the extension directory of the project
     * @return string the extension directory of the project
     */
    function getExtensionDirectory() {
        return WOLFF_EXTENSION_DIR;
    }


    /**
     * Returns the cache directory of the project
     * @return string the cache directory of the project
     */
    function getCacheDirectory() {
        return WOLFF_CACHE_DIR;
    }

    
    /**
     * Returns the title of the project
     * @return string the title of the project
     */
    function getPageTitle() {
        return WOLFF_PAGE_TITLE;
    }

    
    /**
     * Returns the main page of the project
     * @return string the main page of the project
     */
    function getMainPage() {
        return WOLFF_MAIN_PAGE;
    }

}