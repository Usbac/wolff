<?php

namespace Core;

class Cache
{

    const FILENAME = "tmp_%s.php";
    const EXPIRATION_TIME = 604800; //One week


    public function __construct()
    {
    }


    /**
     * Delete the cache files that have expired
     */
    public static function initialize() 
    {
        $files = glob(getServerRoot() . getCacheDirectory() . '*.php');

        foreach ($files as $file) {
            if (self::expired($file)) {
                unlink($file);
            }
        }
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
        if (!file_exists($dir)) {
            return false;
        }

        return (time() - filectime($dir) > self::EXPIRATION_TIME);
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