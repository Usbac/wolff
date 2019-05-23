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
            return WOLFF_SERVER;
        }
    }

    if (!function_exists('getDB')) {

        /**
         * Returns the current database
         * @return string the current database
         */
        function getDB()
        {
            return WOLFF_DB;
        }
    }

    if (!function_exists('getDBMS')) {

        /**
         * Returns the current database management system
         * @return string the current database management system
         */
        function getDBMS()
        {
            return WOLFF_DBMS;
        }
    }

    if (!function_exists('getDBUser')) {

        /**
         * Returns the current database username
         * @return string the current database username
         */
        function getDBUser()
        {
            return WOLFF_DBUSERNAME;
        }
    }

    if (!function_exists('getDBPass')) {

        /**
         * Returns the current database username password
         * @return string the current database username password
         */
        function getDBPass()
        {
            return WOLFF_DBPASSWORD;
        }
    }

    /**
     *  DIRECTORIES
     */

    if (!function_exists('getDirectory')) {

        /**
         * Returns the root directory of the project
         * @return string the root directory of the project
         */
        function getDirectory()
        {
            return WOLFF_ROOT_DIR;
        }
    }

    if (!function_exists('getSystemDirectory')) {

        /**
         * Returns the system directory of the project
         * @return string the system directory of the project
         */
        function getSystemDirectory()
        {
            return WOLFF_SYS_DIR;
        }
    }

    if (!function_exists('getAppDirectory')) {

        /**
         * Returns the app directory of the project
         * @return string the app directory of the project
         */
        function getAppDirectory()
        {
            return WOLFF_APP_DIR;
        }
    }

    if (!function_exists('getPublicDirectory')) {

        /**
         * Returns the public directory of the project
         * @return string the public directory of the project
         */
        function getPublicDirectory()
        {
            return WOLFF_PUBLIC_DIR;
        }
    }

    if (!function_exists('getExtensionDirectory')) {

        /**
         * Returns the extension directory of the project
         * @return string the extension directory of the project
         */
        function getExtensionDirectory()
        {
            return WOLFF_EXTENSION_DIR;
        }
    }

    if (!function_exists('getCacheDirectory')) {

        /**
         * Returns the cache directory of the project
         * @return string the cache directory of the project
         */
        function getCacheDirectory()
        {
            return WOLFF_CACHE_DIR;
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
            return WOLFF_PAGE_TITLE;
        }
    }

    if (!function_exists('getMainPage')) {

        /**
         * Returns the main page of the project
         * @return string the main page of the project
         */
        function getMainPage()
        {
            return WOLFF_MAIN_PAGE;
        }
    }

    if (!function_exists('getLanguage')) {

        /**
         * Returns the language currently used by the project
         * @return string the language name
         */
        function getLanguage()
        {
            return WOLFF_LANGUAGE;
        }
    }

    if (!function_exists('wolffVersion')) {

        /**
         * Returns the current version of Wolff defined in the composer.json file
         * @return string The current version of Wolff defined in the composer.json file
         */
        function wolffVersion()
        {
            return WOLFF_VERSION;
        }
    }

}
