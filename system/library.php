<?php

class Library {

    /**
     * Sanitize an url
     * @param url the url
     * @return string the url sanitized
     */
    public static function sanitizeURL($url) {
        return filter_var(rtrim(strtolower($url), '/'), FILTER_SANITIZE_URL);
    }


    /**
     * Checks if the model exists in the indicated directory
     * @param dir the directory of the model
     * @return boolean true if the model exists, false otherwise 
     */
    public static function modelExists($dir) {
        $file_path = MAIN . 'model/' . $dir . '.php';
        return file_exists($file_path);
    }


    /**
     * Returns the complete path of the model
     * @param dir the directory of the model
     * @return string the complete path of the model
     */
    public static function getModelPath($dir) {
        return MAIN . 'model/' . $dir . '.php';
    }


    /**
     * Checks if the controller exists in the indicated directory
     * @param dir the directory of the controller
     * @return boolean true if the controller exists, false otherwise 
     */
    public static function controllerExists($dir) {
        $file_path = MAIN . 'controller/' . $dir . '.php';
        return file_exists($file_path);
    }


    /**
     * Checks if the controller's function exists
     * @param dir the directory of the controller
     * @return boolean true if the controller's function exists, false otherwise 
     */
    public static function ControllerFuncExists($dir) {
        //Remove the function from the url and save the function name
        $dir = explode('/', $dir);
        $function = array_pop($dir);
        $dir = implode('/', $dir);

        if (!Library::controllerExists($dir)) {
            return false;
        }

        include(Library::getControllerPath($dir));
        $class = 'Controller_' . @end(explode('/', $dir));
        return method_exists(new $class, $function);
    }


    /**
     * Returns the complete path of the controller
     * @param dir the directory of the controller
     * @return string the complete path of the controller
     */
    public static function getControllerPath($dir) {
        return MAIN . 'controller/' . $dir . '.php';
    }

    
    /**
     * Checks if the language file exists in the indicated directory
     * @param dir the directory of the language file
     * @param language the language selected (it will take the default language if no language is specified)
     * @return boolean true if the language file exists, false otherwise 
     */
    public static function languageExists($dir, $language = LANGUAGE) {
        $file_path = MAIN . 'language/' . $language . '/' . $dir . '.php';
        return file_exists($file_path);
    }

    
    /**
     * Returns the complete path of the language
     * @param dir the directory of the language
     * @return string the complete path of the language
     */
    public static function getLanguagePath($dir, $language = LANGUAGE) {
        return MAIN . 'language/' . $language . '/' . $dir . '.php';
    }

    
    /**
     * Checks if the view exists in the indicated directory
     * @param dir the directory of the view
     * @return boolean true if the view exists, false otherwise 
     */
    public static function viewExists($dir) {
        $file_path = MAIN . 'view/' . $dir . '.html';
        return file_exists($file_path); 
    }

    
    /**
     * Returns the complete path of the view
     * @param dir the directory of the view
     * @return string the complete path of the view
     */
    public static function getViewPath($dir) {
        return MAIN . 'view/' . $dir . '.php';
    }


    /**
     * Checks if the substring is present in another string
     * @param str the main string
     * @param needle substring you are looking for
     * @return boolean true if the substring is present in the string, false otherwise
     */
    public static function strContains($str, $needle) {
        return strpos($str, $needle) !== false;
    }

}