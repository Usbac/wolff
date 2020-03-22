<?php

namespace Wolff\Core;

use Wolff\Utils\Str;

/**
 * @method static emergency(string $message, array $values = [])
 * @method static alert(string $message, array $values = [])
 * @method static critical(string $message, array $values = [])
 * @method static error(string $message, array $values = [])
 * @method static warning(string $message, array $values = [])
 * @method static notice(string $message, array $values = [])
 * @method static info(string $message, array $values = [])
 * @method static debug(string $message, array $values = [])
 */
class Log
{

    const FOLDER_PERMISSIONS = 0755;
    const DATE_FORMAT = 'H:i:s';
    const FORMAT = '[%s] [%s] %s: %s';
    const FOLDER_PATH = CONFIG['root_dir'] . CONFIG['system_dir'] . 'logs';
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
        return CONFIG['log_on'];
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
     * Logs a general message
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
     * Writes content to the current log file
     *
     * @param  string  $data the content to append
     */
    private static function writeToFile(string $data)
    {
        self::mkdir();
        $filename = self::FOLDER_PATH . '/' . date('m-d-Y') . '.log';
        file_put_contents($filename, $data . PHP_EOL, FILE_APPEND);
    }


    /**
     * Creates the logs folder if it doesn't exists
     */
    private static function mkdir()
    {
        if (!file_exists(self::FOLDER_PATH)) {
            mkdir(self::FOLDER_PATH, self::FOLDER_PERMISSIONS, true);
        }
    }
}
