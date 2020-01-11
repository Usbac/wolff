<?php

namespace Core;

use Utilities\Str;

class Log
{

    const FOLDER_NAME = 'log';
    const FILE_PERMISSIONS = 0755;
    const DATE_FORMAT = 'H:i:s';
    const FORMAT = '[{date}] [{ip}] {level}: {message}';

    const EMERGENCY = 'Emergency';
    const ALERT     = 'Alert';
    const CRITICAL  = 'Critical';
    const ERROR     = 'Error';
    const WARNING   = 'Warning';
    const NOTICE    = 'Notice';
    const INFO      = 'Info';
    const DEBUG     = 'Debug';


    /**
     * Returns true if the log is enabled, false otherwise
     * @return bool true if the log is enabled, false otherwise
     */
    public static function isEnabled()
    {
        return CONFIG['log_on'];
    }


    /**
     * Log a emergency message
     *
     * @param  string  $message the message
     * @param  array  $values  the values to interpolate
     */
    public static function emergency(string $message, array $values = [])
    {
        self::log(self::EMERGENCY, $message, $values);
    }


    /**
     * Log an alert message
     *
     * @param  string  $message the message
     * @param  array  $values  the values to interpolate
     */
    public static function alert(string $message, array $values = [])
    {
        self::log(self::ALERT, $message, $values);
    }


    /**
     * Log a critical message
     *
     * @param  string  $message the message
     * @param  array  $values  the values to interpolate
     */
    public static function critical(string $message, array $values = [])
    {
        self::log(self::CRITICAL, $message, $values);
    }


    /**
     * Log an error message
     *
     * @param  string  $message the message
     * @param  array  $values  the values to interpolate
     */
    public static function error(string $message, array $values = [])
    {
        self::log(self::ERROR, $message, $values);
    }


    /**
     * Log a warning message
     *
     * @param  string  $message the message
     * @param  array  $values  the values to interpolate
     */
    public static function warning(string $message, array $values = [])
    {
        self::log(self::WARNING, $message, $values);
    }


    /**
     * Log a notice message
     *
     * @param  string  $message the message
     * @param  array  $values  the values to interpolate
     */
    public static function notice(string $message, array $values = [])
    {
        self::log(self::NOTICE, $message, $values);
    }


    /**
     * Log an info message
     *
     * @param  string  $message the message
     * @param  array  $values  the values to interpolate
     */
    public static function info(string $message, array $values = [])
    {
        self::log(self::INFO, $message, $values);
    }


    /**
     * Log a debug message
     *
     * @param  string  $message the message
     * @param  array  $values  the values to interpolate
     */
    public static function debug(string $message, array $values = [])
    {
        self::log(self::DEBUG, $message, $values);
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

        $values = [
            'date'    => date(self::DATE_FORMAT),
            'ip'      => getClientIP(),
            'level'   => $level,
            'message' => $message
        ];

        $log = Str::interpolate(self::FORMAT, $values);
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
        $filename = CONFIG['system_dir'] . self::FOLDER_NAME . '/' . date('m-d-Y') . '.log';
        file_put_contents($filename, $data . PHP_EOL, FILE_APPEND);
    }


    /**
     * Create the logs folder if it doesn't exists
     */
    private static function mkdir()
    {
        $folder_path = CONFIG['system_dir'] . self::FOLDER_NAME;

        if (!file_exists($folder_path)) {
            mkdir($folder_path, self::FILE_PERMISSIONS, true);
        }
    }
}
