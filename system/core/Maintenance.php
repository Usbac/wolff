<?php

namespace Core;

use Utilities\Str;

class Maintenance
{

    /**
     * Filename of the ip whitelist file.
     *
     * @var string
     */
    private static $file = WOLFF_ROOT_DIR . 'system/definitions/maintenance_whitelist.txt';


    const READ_ERROR = "Couldn't read the maintenance whitelist file";


    /**
     * Returns true if the maintenance mode is enabled, false otherwise
     * @return bool true if the maintenance mode is enabled, false otherwise
     */
    public static function isEnabled()
    {
        return WOLFF_MAINTENANCE_ON;
    }

    /**
     * Returns an array of the IP in the whitelist
     * @return array An array of the IP in the whitelist, false if an error happends
     */
    public static function getAllowedIPs()
    {
        if (!is_file(self::$file)) {
            return false;
        }

        if (!$content = file_get_contents(self::$file)) {
            Log::warning(self::READ_ERROR);

            return false;
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
            Log::warning(self::READ_ERROR);

            return false;
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
     * @return bool true if the IP is removed/doesn't exists in the whitelist
     */
    public static function removeAllowedIP(string $ip)
    {
        if (!$ip = filter_var($ip, FILTER_VALIDATE_IP)) {
            return false;
        }

        self::createFile();

        if (!$content = file_get_contents(self::$file)) {
            Log::warning(self::READ_ERROR);

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
    public static function createFile()
    {
        if (!is_file(self::$file)) {
            file_put_contents(self::$file, '');
        }
    }


    /**
     * Returns true if the current client IP is in the whitelist, false otherwise
     * @return bool true if the current client IP is in the whitelist, false otherwise
     */
    public static function isClientAllowed()
    {
        if (self::getAllowedIPs() === false) {
            return false;
        }

        return in_array(getClientIP(), self::getAllowedIPs());
    }

}
