<?php

namespace Core;

class Cache
{

    const FILENAME = "tmp_%s.php";
    const EXPIRATION_TIME = 604800; //One week
    const FOLDER_PERMISSIONS = 0755;


    public function __construct()
    {
    }


    /**
     * Delete the cache files that have expired
     */
    public static function initialize()
    {
        $files = glob(getCacheDirectory() . '*.php');

        foreach ($files as $file) {
            if (self::expired($file)) {
                unlink($file);
            }
        }
    }


    /**
     * Returns true if the cache is enabled, false otherwise
     * @return bool true if the cache is enabled, false otherwise
     */
    public static function isEnabled()
    {
        return CONFIG['cache_on'];
    }


    /**
     * Return the specified cache file path
     *
     * @param  string  $dir  the cache filename
     *
     * @return string return the specified cache file path
     */
    public static function get(string $dir)
    {
        return getCacheDirectory() . self::getFilename($dir);
    }


    /**
     * Create the cache file if doesn't exists and return its path
     *
     * @param  string  $dir  the cache filename
     * @param  string  $content  the original file content
     *
     * @return string the cache file path
     */
    public static function set(string $dir, string $content)
    {
        $file_path = self::get($dir);

        self::mkdir();

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
    public static function mkdir()
    {
        $folder_path = getCacheDirectory();
        if (!file_exists($folder_path)) {
            mkdir($folder_path, self::FOLDER_PERMISSIONS, true);
        }
    }


    /**
     * Checks if the specified cache exists
     *
     * @param  string  $dir  the cache file directory
     *
     * @return bool true if the cache exists, false otherwise
     */
    public static function has(string $dir)
    {
        $file_path = getCacheDirectory() . self::getFilename($dir);

        return is_file($file_path);
    }


    /**
     * Delete the specified cache file
     *
     * @param  string  $dir  the cache to delete, if its empty all the cache will be deleted
     *
     * @return bool true if the item was successfully removed, false otherwise
     */
    public static function delete(string $dir)
    {
        $file_path = getCacheDirectory() . self::getFilename($dir);

        if (is_file($file_path)) {
            unlink($file_path);

            return true;
        }

        return false;
    }


    /**
     * Delete all the cache files
     *
     * @return bool true if the item was successfully removed, false otherwise
     */
    public static function clear()
    {
        return deleteFilesInDir(getCacheDirectory());
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
