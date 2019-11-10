<?php

namespace {

    /**
     *  SERVER
     */


    if (!function_exists('getServer')) {

        /**
         * Returns the current server
         * @return string the current server
         */
        function getServer()
        {
            return CONFIG['server'] ?? '';
        }
    }

    if (!function_exists('getDB')) {

        /**
         * Returns the current database
         * @return string the current database
         */
        function getDB()
        {
            return CONFIG['db'] ?? '';
        }
    }

    if (!function_exists('getDBMS')) {

        /**
         * Returns the current database management system
         * @return string the current database management system
         */
        function getDBMS()
        {
            return CONFIG['dbms'] ?? '';
        }
    }

    if (!function_exists('getDbUser')) {

        /**
         * Returns the current database username
         * @return string the current database username
         */
        function getDbUser()
        {
            return CONFIG['db_username'] ?? '';
        }
    }

    if (!function_exists('getDbPass')) {

        /**
         * Returns the current database username password
         * @return string the current database username password
         */
        function getDbPass()
        {
            return CONFIG['db_password'] ?? '';
        }
    }

    /**
     *  DIRECTORIES
     */

    if (!function_exists('getDir')) {

        /**
         * Returns the root directory of the project
         *
         * @param  string  $path  the optional path to append
         *
         * @return string the root directory of the project
         */
        function getDir(string $path = '')
        {
            return CONFIG['root_dir'] . $path;
        }
    }

    if (!function_exists('getProjectDir')) {

        /**
         * Returns the root directory of the project relative to the server root
         *
         * @param  string  $path  the optional path to append
         *
         * @return string the root directory of the project relative to the server root
         */
        function getProjectDir(string $path = '')
        {
            return substr(CONFIG['root_dir'], strlen($_SERVER['DOCUMENT_ROOT'])) . $path;
        }
    }

    if (!function_exists('getSystemDir')) {

        /**
         * Returns the system directory of the project
         *
         * @param  string  $path  the optional path to append
         *
         * @return string the system directory of the project
         */
        function getSystemDir(string $path = '')
        {
            return CONFIG['system_dir'] . $path;
        }
    }

    if (!function_exists('getAppDir')) {

        /**
         * Returns the app directory of the project
         *
         * @param  string  $path  the optional path to append
         *
         * @return string the app directory of the project
         */
        function getAppDir(string $path = '')
        {
            return CONFIG['app_dir'] . $path;
        }
    }

    if (!function_exists('getPublic')) {

        /**
         * Returns the public directory of the project
         * relative to the server root
         *
         * @param  string  $path  the optional path to append
         *
         * @return string the public directory of the project
         */
        function getPublic(string $path = '')
        {
            return substr(CONFIG['public_dir'], strlen($_SERVER['DOCUMENT_ROOT'])) . $path;
        }
    }

    if (!function_exists('getExtensionDir')) {

        /**
         * Returns the extension directory of the project
         *
         * @param  string  $path  the optional path to append
         *
         * @return string the extension directory of the project
         */
        function getExtensionDir(string $path = '')
        {
            return CONFIG['extension_dir'] . $path;
        }
    }

    if (!function_exists('getCacheDir')) {

        /**
         * Returns the cache directory of the project
         *
         * @param  string  $path  the optional path to append
         *
         * @return string the cache directory of the project
         */
        function getCacheDir(string $path = '')
        {
            return CONFIG['cache_dir'] . $path;
        }
    }

    /**
     *  GENERAL
     */

    if (!function_exists('getPageTitle')) {

        /**
         * Returns the title of the project
         * @return string the title of the project
         */
        function getPageTitle()
        {
            return CONFIG['title'] ?? '';
        }
    }

    if (!function_exists('getMainPage')) {

        /**
         * Returns the main page of the project
         * @return string the main page of the project
         */
        function getMainPage()
        {
            return CONFIG['main_page'] ?? '';
        }
    }

    if (!function_exists('getLanguage')) {

        /**
         * Returns the language currently used by the project
         * @return string the language name
         */
        function getLanguage()
        {
            return CONFIG['language'] ?? '';
        }
    }

    if (!function_exists('wolffVersion')) {

        /**
         * Returns the current version of Wolff
         * @return string the current version of Wolff
         */
        function wolffVersion()
        {
            return CORE_CONFIG['version'];
        }
    }

}
