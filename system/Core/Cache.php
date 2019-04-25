<?php

namespace Core;

class Cache
{

    /**
     * List of specific cache life times in minutes.
     *
     * @var array
     */
    private static $remembered = [];

    /**
     * General cache life time in minutes.
     *
     * @var int
     */
    private static $time = 1440;

    const FILENAME = "tmp_%s.php";


    public function __construct()
    {
    }


    /**
     * Create the cache file if doesn't exists and return its path
     *
     * @param  string  $dir  the view directory
     * @param  string  $content  the original file content
     *
     * @return string the cache file path
     */
    public static function get(string $dir, string $content)
    {
        $file_path = getServerRoot() . getCacheDirectory() . self::getFilename($dir);

        self::createFolder();

        if (self::expired($dir)) {
            unlink($file_path);
        }

        if (!file_exists($file_path)) {
            $file = fopen($file_path, 'w');
            fwrite($file, $content);
            fclose($file);
        }

        return $file_path;
    }


    /**
     * Returns true if the cache file has expired, false otherwise
     *
     * @param  string  $dir  the cache file directory
     *
     * @return bool true if the cache file has expired, false otherwise
     */
    public static function expired($dir)
    {
        $file_path = getServerRoot() . getCacheDirectory() . self::getFilename($dir);
        if (!file_exists($file_path)) {
            return false;
        }

        $file_time = (time() - filemtime($file_path)) / 60;

        if (array_key_exists($dir, self::$remembered)) {
            return ($file_time > self::$remembered[$dir]);
        }

        return ($file_time > self::$time);
    }


    /**
     * Set a cache file life time
     *
     * @param  string  $dir  the cache file directory
     * @param  int  $time  the cache file life time
     */
    public static function remember($dir, $time)
    {
        self::$remembered[$dir] = $time;
    }


    /**
     * Create the cache folder if it doesn't exists
     */
    public static function createFolder()
    {
        $folder_path = getServerRoot() . getCacheDirectory();
        if (!file_exists($folder_path)) {
            mkdir($folder_path, 0777, true);
        }
    }


    /**
     * Checks if the specified cache exists
     *
     * @param  string  $dir  the cache file directory
     *
     * @return bool true if the cache exists, false otherwise
     */
    public static function exists(string $dir = '')
    {
        $folder_path = getServerRoot() . getCacheDirectory();

        if (!empty($dir)) {
            $file_path = $folder_path . self::getFilename($dir);

            return is_file($file_path);
        }

        return !empty(glob($folder_path . '*'));
    }


    /**
     * Delete all the cache files or the specified one
     *
     * @param  string  $dir  the cache to delete, if its empty all the cache will be deleted
     */
    public static function clear(string $dir = '')
    {
        $folder_path = getServerRoot() . getCacheDirectory();

        if (empty($dir)) {
            deleteFilesInDir($folder_path);

            return;
        }

        $file_path = $folder_path . self::getFilename($dir);

        if (is_file($file_path)) {
            unlink($file_path);
        }
    }


    /**
     * Get the cache format name of a file
     *
     * @param  string  $dir  the cache file
     *
     * @return string the filename with the cache format
     */
    public static function getFilename(string $dir)
    {
        return sprintf(self::FILENAME, $dir);
    }

}