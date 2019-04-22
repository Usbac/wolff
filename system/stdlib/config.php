<?php

namespace {

    /**
     *  SERVER
     */


    /**
     * Returns the current server
     * @return string the current server
     */
    function getServer() {
        return WOLFF_SERVER;
    }


    /**
     * Returns the current database
     * @return string the current database
     */
    function getDB() {
        return WOLFF_DB;
    }


    /**
     * Returns the current database managment system
     * @return string the current database managment system
     */
    function getDBMS() {
        return WOLFF_DBMS;
    }


    /**
     * Returns the current database username
     * @return string the current database username
     */
    function getDBUser() {
        return WOLFF_DBUSERNAME;
    }


    /**
     * Returns the current database username password
     * @return string the current database username password
     */
    function getDBPass() {
        return WOLFF_DBPASSWORD;
    }

    
    /**
     *  DIRECTORIES
     */


    /**
     * Returns the root directory of the project
     * @return string the root directory of the project
     */
    function getDirectory() {
        return WOLFF_ROOT_DIR;
    }


    /**
     * Returns the absolute root directory of the project
     * @return string the absolute root directory of the project
     */
   function getAbsoluteDirectory() {
       return getServerRoot() . getDirectory();
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
     *  GENERAL
     */


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


    /**
     * Returns the language currently used by the project
     * @return string the language name
     */
    function getLanguage() {
        return WOLFF_LANGUAGE;
    }

    
    /**
     * Returns the current version of Wolff defined in the composer.json file
     * @return string The current version of Wolff defined in the composer.json file
     */
    function wolffVersion() {
        return WOLFF_VERSION;
    }

    
    /**
     *  EXTRA
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
    
}