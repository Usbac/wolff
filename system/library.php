<?php

namespace System;

class Library {

    private static $benchmark = [];

    
    /**
     * Sanitize an url
     * @param url the url
     * @return string the url sanitized
     */
    public static function sanitizeURL(string $url) {
        return filter_var(rtrim(strtolower($url), '/'), FILTER_SANITIZE_URL);
    }


    /**
     * Checks if the model exists in the indicated directory
     * @param dir the directory of the model
     * @return boolean true if the model exists, false otherwise 
     */
    public function modelExists(string $dir) {
        $file_path = 'app/model/' . $dir . '.php';
        return file_exists($file_path);
    }


    /**
     * Returns the complete path of the model
     * @param dir the directory of the model
     * @return string the complete path of the model
     */
    public function getModelPath(string $dir) {
        return 'app/model/' . $dir . '.php';
    }


    /**
     * Checks if the controller exists in the indicated directory
     * @param dir the directory of the controller
     * @return boolean true if the controller exists, false otherwise 
     */
    public function controllerExists(string $dir) {
        $file_path = 'app/controller/' . $dir . '.php';
        return file_exists($file_path);
    }


    /**
     * Checks if the controller's function exists
     * @param dir the directory of the controller
     * @return boolean true if the controller's function exists, false otherwise 
     */
    public function functionExists(string $dir) {
        //Remove the function from the url and save the function name
        $function = substr($dir, strrpos($dir, '/') + 1);
        $dir = substr($dir, 0, strrpos($dir, '/'));

        if (!$this->controllerExists($dir)) {
            return false;
        }

        $dir = str_replace('/', '\\', $dir);
        $class = '\\App\\Controller\\' . $dir;
        $method = null;

        try {
            $class = new \ReflectionClass($class);
            $method = $class->getMethod($function);
        } catch(\Exception $e) {
            return false;
        }
        
        return $method !== null;
    }


    /**
     * Returns the complete path of the controller
     * @param dir the directory of the controller
     * @return string the complete path of the controller
     */
    public function getControllerPath(string $dir) {
        return 'app/controller/' . $dir . '.php';
    }

    
    /**
     * Checks if the language file exists in the indicated directory
     * @param dir the directory of the language file
     * @param language the language selected (it will take the default language if no language is specified)
     * @return boolean true if the language file exists, false otherwise 
     */
    public function languageExists(string $dir, string $language = LANGUAGE) {
        $file_path = 'app/language/' . $language . '/' . $dir . '.php';
        return file_exists($file_path);
    }

    
    /**
     * Returns the complete path of the language
     * @param dir the directory of the language
     * @return string the complete path of the language
     */
    public function getLanguagePath(string $dir, string $language = LANGUAGE) {
        return 'app/language/' . $language . '/' . $dir . '.php';
    }


    /**
     * Checks if the library exists in the indicated directory
     * @param dir the directory of the library
     * @return boolean true if the library exists, false otherwise 
     */
    public function libraryExists(string $dir) {
        $file_path = 'app/library/' . $dir . '.php';
        return file_exists($file_path); 
    }

    
    /**
     * Checks if the view exists in the indicated directory
     * @param dir the directory of the view
     * @return boolean true if the view exists, false otherwise 
     */
    public function viewExists(string $dir) {
        $file_path = 'app/view/' . $dir . '.html';
        return file_exists($file_path); 
    }

    
    /**
     * Returns the complete path of the view
     * @param dir the directory of the view
     * @return string the complete path of the view
     */
    public function getViewPath(string $dir) {
        return 'app/view/' . $dir . '.php';
    }


    /**
     * Checks if the substring is present in another string
     * @param str the rapp/ing
     * @param needle substring you are looking for
     * @return boolean true if the substring is present in the string, false otherwise
     */
    public function strContains(string $str, string $needle) {
        return strpos($str, $needle) !== false;
    }


    /**
     * Start the benchmark
     */
    public function benchmarkStart() {
        Library::$benchmark[0] = array(
            'time'        => microtime(true),
            'memoryUsed'  => memory_get_usage()
        );
    }


    /**
     * End the benchmark
     */
    public function benchmarkEnd() {
        Library::$benchmark[1] = array(
            'time'        => microtime(true),
            'memoryUsed'  => memory_get_usage()
        );
    }


    /**
     * Return the benchmark result between the start and end benchmark points
     * @return array the benchmark result as an assosiative array
     */
    public function getBenchmark() {
        $result = [];
        foreach (array_keys(Library::$benchmark[0]) as $key) {
            $result[$key] = Library::$benchmark[1][$key] - Library::$benchmark[0][$key];
        }
        
        return $result;
    }

}