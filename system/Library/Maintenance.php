<?php

namespace System\Library;

class Maintenance {


    /**
     * Filename of the ip whitelist file.
     *
     * @var string
     */
    private static $filename = __DIR__ . DIRECTORY_SEPARATOR . 'maintenance_whitelist.txt';

    
    const READ_ERROR = "Warning: Couldn't read the maintenance whitelist file";


    /**
     * Returns an array of the IP in the whitelist
     * @return array An array of the IP in the whitelist, false if an error happends
     */
    public static function getAllowedIPs() {
        if (!is_file(self::$filename)) {
            return false;
        }

        if (!$content = file_get_contents(self::$filename)) {
            error_log(self::READ_ERROR);
            return false;
        }

        return explode(PHP_EOL, $content);
    }


    /**
     * Add an IP to the whitelist
     * @param string $ip the IP to add
     * @return bool true if the IP is added/exists in the whitelist
     */
    public static function addAllowedIP(string $ip) {
        if (!$ip = filter_var($ip, FILTER_VALIDATE_IP)) {
            return false;
        }

        self::createFile();

        if (!$content = file_get_contents(self::$filename)) {
            if (is_writable(self::$filename)) {
                file_put_contents(self::$filename, $ip);
                return true;
            }
            error_log(self::READ_ERROR);
            return false;
        }

        if (strContains($content, $ip)) {
            return true;
        }

        file_put_contents(self::$filename, PHP_EOL . $ip, FILE_APPEND | LOCK_EX);
        return true;
    }


    /**
     * Remove an IP from the whitelist
     * @param string $ip the IP to remove
     * @return bool true if the IP is removed/doesn't exists in the whitelist
     */
    public static function removeAllowedIP(string $ip) {
        if (!$ip = filter_var($ip, FILTER_VALIDATE_IP)) {
            return false;
        }

        self::createFile();

        if (!$content = file_get_contents(self::$filename)) {
            error_log(self::READ_ERROR);
            return false;
        }

        if (!strContains($content, $ip)) {
            return true;
        }

        $content = str_replace($ip, '', $content);
        $content = implode(PHP_EOL, array_filter(explode(PHP_EOL, $content)));
        file_put_contents(self::$filename, $content);
        return true;
    }


    /**
     * Create the text file with the IP whitelist 
     */
    public function createFile() {
        if (!is_file(self::$filename)) {
            file_put_contents(self::$filename, '');
        }
    }


    /**
     * Returns true if the current client IP is in the whitelist, false otherwise
     * @return bool true if the current client IP is in the whitelist, false otherwise
     */
    public function isClientAllowed() {
        if (self::getAllowedIPs() === false) {
            return false;
        }
        
        return in_array(getClientIP(), self::getAllowedIPs());
    }

}