<?php

namespace Core;

use Utilities\Str;

class Log
{

    const FOLDER_NAME = 'log';
    const FILE_PERMISSIONS = 0755;
    const DATE_FORMAT = 'm-d-Y H:i:s';
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
     * Log a emergency message
     *
     * @param  string  $message the message
     */
    public static function emergency(string $message)
    {
        self::log(self::EMERGENCY, $message);
    }


    /**
     * Log an alert message
     *
     * @param  string  $message the message
     */
    public static function alert(string $message)
    {
        self::log(self::ALERT, $message);
    }


    /**
     * Log a critical message
     *
     * @param  string  $message the message
     */
    public static function critical(string $message)
    {
        self::log(self::CRITICAL, $message);
    }


    /**
     * Log an error message
     *
     * @param  string  $message the message
     */
    public static function error(string $message)
    {
        self::log(self::ERROR, $message);
    }


    /**
     * Log a warning message
     *
     * @param  string  $message the message
     */
    public static function warning(string $message)
    {
        self::log(self::WARNING, $message);
    }


    /**
     * Log a notice message
     *
     * @param  string  $message the message
     */
    public static function notice(string $message)
    {
        self::log(self::NOTICE, $message);
    }


    /**
     * Log an info message
     *
     * @param  string  $message the message
     */
    public static function info(string $message)
    {
        self::log(self::INFO, $message);
    }


    /**
     * Log a debug message
     *
     * @param  string  $message the message
     */
    public static function debug(string $message)
    {
        self::log(self::DEBUG, $message);
    }


    /**
     * Log a general message
     *
     * @param  string  $level the message level
     * @param  string  $message the message
     */
    private static function log(string $level, string $message)
    {
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
        $filename = getSystemDir() . self::FOLDER_NAME . '/' . date("m-d-Y") . '.log';
        file_put_contents($filename, $data . PHP_EOL, FILE_APPEND);
    }


    /**
     * Create the logs folder if it doesn't exists
     */
    private static function mkdir()
    {
        $folder_path = getSystemDir() . self::FOLDER_NAME;

        if (!file_exists($folder_path)) {
            mkdir($folder_path, self::FILE_PERMISSIONS, true);
        }
    }
}
