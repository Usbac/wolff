<?php

namespace {


    if (!function_exists('getControllerPath')) {

        /**
         * Returns the complete path of the controller
         *
         * @param  string  $dir  the directory of the controller
         *
         * @return string the complete path of the controller
         */
        function getControllerPath(string $dir)
        {
            return getAppDirectory() . 'controllers/' . $dir . '.php';
        }
    }

    if (!function_exists('controllerExists')) {

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
    }

    if (!function_exists('functionExists')) {

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
            $function = substr($dir, $lastSlash + 1);
            $dir = substr($dir, 0, $lastSlash);

            $class = 'Controller\\' . str_replace('/', '\\', $dir);

            try {
                $class = new ReflectionClass($class);
                $class->getMethod($function);
            } catch (Exception $e) {

                return false;
            }

            return true;
        }
    }

    if (!function_exists('languageExists')) {

        /**
         * Checks if the language file exists in the indicated directory
         *
         * @param  string  $dir  the directory of the language file
         * @param  string  $language  the language selected (it will take the default language if no language is specified)
         *
         * @return boolean true if the language file exists, false otherwise
         */
        function languageExists(string $dir, string $language = CONFIG['language'])
        {
            return file_exists(getLanguagePath($dir, $language));
        }
    }

    if (!function_exists('getLanguagePath')) {

        /**
         * Returns the complete path of the language
         *
         * @param  string  $dir  the directory of the language
         *
         * @return string $language the complete path of the language
         */
        function getLanguagePath(string $dir, string $language = CONFIG['language'])
        {
            return getAppDirectory() . 'languages/' . $language . '/' . $dir . '.php';
        }
    }

    if (!function_exists('viewExists')) {

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
    }

    if (!function_exists('getViewPath')) {

        /**
         * Returns the complete path of the view
         *
         * @param  string  $dir  the directory of the view
         *
         * @return string the complete path of the view
         */
        function getViewPath(string $dir)
        {
            return getAppDirectory() . CORE_CONFIG['views_folder'] . '/' . $dir . CORE_CONFIG['views_format'];
        }
    }
}
