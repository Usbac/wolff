<?php

namespace {


    /**
     * Checks if the controller exists in the indicated directory
     *
     * @param  string  $dir  the directory of the controller
     *
     * @return boolean true if the controller exists, false otherwise
     */
    function controllerExists(string $dir)
    {
        return file_exists(getControllerPath($dir));
    }


    /**
     * Returns the complete path of the controller
     *
     * @param  string  $dir  the directory of the controller
     *
     * @return string the complete path of the controller
     */
    function getControllerPath(string $dir)
    {
        return getServerRoot() . getAppDirectory() . 'controllers/' . $dir . '.php';
    }


    /**
     * Returns true if the controller's function exists, false otherwise
     *
     * @param  string  $dir  the directory of the controller
     *
     * @return boolean true if the controller's function exists, false otherwise
     */
    function functionExists(string $dir)
    {
        //Remove the function from the url and save the function name
        $lastSlash = strrpos($dir, '/');
        $function  = substr($dir, $lastSlash + 1);
        $dir       = substr($dir, 0, $lastSlash);

        $class = 'Controller\\' . pathToNamespace($dir);

        try {
            $class = new ReflectionClass($class);
            $class->getMethod($function);
        } catch (Exception $e) {
            error_log($e->getMessage());

            return false;
        }

        return true;
    }


    /**
     * Checks if the language file exists in the indicated directory
     *
     * @param  string  $dir  the directory of the language file
     * @param  string  $language  the language selected (it will take the default language if no language is specified)
     *
     * @return boolean true if the language file exists, false otherwise
     */
    function languageExists(string $dir, string $language = WOLFF_LANGUAGE)
    {
        return file_exists(getLanguagePath($dir, $language));
    }


    /**
     * Returns the complete path of the language
     *
     * @param  string  $dir  the directory of the language
     *
     * @return string $language the complete path of the language
     */
    function getLanguagePath(string $dir, string $language = WOLFF_LANGUAGE)
    {
        return getServerRoot() . getAppDirectory() . 'languages/' . $language . '/' . $dir . '.php';
    }


    /**
     * Checks if the library exists in the indicated directory
     *
     * @param  string  $dir  the directory of the library
     *
     * @return boolean true if the library exists, false otherwise
     */
    function libraryExists(string $dir)
    {
        return file_exists(getLibraryPath($dir));
    }


    /**
     * Returns the complete path of the library
     *
     * @param  string  $dir  the directory of the library
     *
     * @return string the complete path of the library
     */
    function getLibraryPath(string $dir)
    {
        return getServerRoot() . getAppDirectory() . 'libraries/' . $dir . '.php';
    }


    /**
     * Checks if the view exists in the indicated directory
     *
     * @param  string  $dir  the directory of the view
     *
     * @return boolean true if the view exists, false otherwise
     */
    function viewExists(string $dir)
    {
        return file_exists(getViewPath($dir));
    }


    /**
     * Returns the complete path of the view
     *
     * @param  string  $dir  the directory of the view
     *
     * @return string the complete path of the view
     */
    function getViewPath(string $dir)
    {
        return getServerRoot() . getAppDirectory() . 'views/' . $dir;
    }
}