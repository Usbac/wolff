<?php

namespace Wolff\Core;

use Wolff\Utils\Str;

class Log
{

    const FOLDER_PERMISSIONS = 0755;
    const DATE_FORMAT = 'H:i:s';
    const FORMAT = '[%s] [%s] %s: %s';
    const LEVELS = [
        'emergency',
        'alert',
        'critical',
        'error',
        'warning',
        'notice',
        'info',
        'debug'
    ];


    /**
     * Returns true if the log is enabled, false otherwise
     * @return bool true if the log is enabled, false otherwise
     */
    public static function isEnabled()
    {
        return Config::get('log_on');
    }


    /**
     * Proxy method to log the messages in
     * different levels.
     *
     * @param  string  $method_name the method name
     * @param  mixed  $args  the method arguments
     */
    public static function __callStatic(string $method_name, $args)
    {
        //If the method is not for logging something or if the log message is empty
        if (!in_array($method_name, self::LEVELS) ||
            ($message = $args[0]) === null) {
            return;
        }

        $values = $args[1] ?? [];

        self::log(ucfirst($method_name), $message, $values);
    }


    /**
     * Log a general message
     *
     * @param  string  $level the message level
     * @param  string  $message the message
     * @param  array  $values  the values to interpolate
     */
    private static function log(string $level, string $message, array $values)
    {
        if (!self::isEnabled()) {
            return;
        }

        $message = Str::interpolate($message, $values);
        $log = sprintf(self::FORMAT, date(self::DATE_FORMAT), \getClientIP(), $level, $message);
        self::writeToFile($log);
    }


    /**
     * Write content to the current log file
     *
     * @param  string  $data the content to append
     */
    private static function writeToFile(string $data)
    {
        self::mkdir();
        $filename = Config::get('system_dir') . '/logs/' . date('m-d-Y') . '.log';
        file_put_contents($filename, $data . PHP_EOL, FILE_APPEND);
    }


    /**
     * Create the logs folder if it doesn't exists
     */
    private static function mkdir()
    {
        $folder_path = Config::get('system_dir') . '/logs';

        if (!file_exists($folder_path)) {
            mkdir($folder_path, self::FOLDER_PERMISSIONS, true);
        }
    }
}
