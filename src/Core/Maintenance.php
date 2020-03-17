<?php

namespace Wolff\Core;

use Wolff\Utils\Str;

class Maintenance
{

    const HEADER_503 = 'HTTP/1.1 503 Service Temporarily Unavailable';
    const NO_READABLE = 'Couldn\'t read the maintenance whitelist file';
    const INVALID_IP = 'Invalid IP format for the maintenance whitelist file';

    /**
     * Filename of the ip whitelist file.
     *
     * @var string
     */
    private static $file = CONFIG['root_dir'] . '/system/maintenance_whitelist.txt';


    /**
     * Returns an array of the IPs in the whitelist
     * @return array An array of the IPs in the whitelist
     */
    public static function getAllowedIPs()
    {
        if (!is_file(self::$file)) {
            return false;
        }

        if (($content = file_get_contents(self::$file)) === false) {
            throw new \Error(self::NO_READABLE);
        }

        return explode(PHP_EOL, $content);
    }


    /**
     * Add an IP to the whitelist
     *
     * @param  string  $ip  the IP to add
     *
     * @return bool true if the IP is added/exists in the whitelist
     */
    public static function addAllowedIP(string $ip)
    {
        if (!$ip = filter_var($ip, FILTER_VALIDATE_IP)) {
            return false;
        }

        self::createFile();

        if (!$content = file_get_contents(self::$file)) {
            if (is_writable(self::$file)) {
                file_put_contents(self::$file, $ip);

                return true;
            }

            throw new \Error(self::NO_READABLE);
        }

        if (Str::contains($content, $ip)) {
            return true;
        }

        file_put_contents(self::$file, PHP_EOL . $ip, FILE_APPEND | LOCK_EX);

        return true;
    }


    /**
     * Remove an IP from the whitelist
     *
     * @param  string  $ip  the IP to remove
     *
     * @return bool true if the IP is removed/doesn't exists in the whitelist, false otherwise
     */
    public static function removeAllowedIP(string $ip)
    {
        if (!$ip = filter_var($ip, FILTER_VALIDATE_IP)) {
            return false;
        }

        self::createFile();

        if (!$content = file_get_contents(self::$file)) {
            throw new \Error(self::NO_READABLE);

            return false;
        }

        if (!Str::contains($content, $ip)) {
            return true;
        }

        $content = str_replace($ip, '', $content);
        $content = implode(PHP_EOL, array_filter(explode(PHP_EOL, $content)));
        file_put_contents(self::$file, $content);

        return true;
    }


    /**
     * Create the text file with the IP whitelist
     */
    private static function createFile()
    {
        if (!is_file(self::$file)) {
            file_put_contents(self::$file, '');
        }
    }


    /**
     * Returns true if the current client IP is in the whitelist, false otherwise
     * @return bool true if the current client IP is in the whitelist, false otherwise
     */
    public static function hasAccess()
    {
        if (self::getAllowedIPs() === false) {
            return false;
        }

        return in_array(getClientIP(), self::getAllowedIPs());
    }


    /**
     * Load the maintenance page
     * Warning: This method stops the current script
     */
    public static function call()
    {
        header(self::HEADER_503);
        Controller::call(CORE_CONFIG['controller_maintenance']);
        exit;
    }

}
