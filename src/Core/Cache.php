<?php

namespace Wolff\Core;

class Cache
{

    const FILENAME_FORMAT = 'tmp_%s.php';
    const EXPIRATION_TIME = 604800; //One week
    const FOLDER_PERMISSIONS = 0755;


    /**
     * Delete the cache files that have expired
     */
    public static function init()
    {
        if (!self::isEnabled()) {
            return;
        }

        $files = glob(self::getDir('*.php'));

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
        return Config::get('cache_on');
    }


    /**
     * Returns the content of the cache file
     *
     * @param  string  $dir  the cache filename
     *
     * @return string return the content of the cache file
     */
    public static function getContent(string $dir)
    {
        $file_path = self::getDir(self::getFilename($dir));

        if (file_exists($file_path)) {
            return file_get_contents($file_path);
        }

        throw new \Error("Cache '$dir' doesn't exists");
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
        $file_path = self::getDir(self::getFilename($dir));

        if (!file_exists($file_path)) {
            self::mkdir();
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
    private static function expired($dir)
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
        if (!file_exists(self::getDir())) {
            mkdir(self::getDir(), self::FOLDER_PERMISSIONS, true);
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
        $file_path = self::getDir(self::getFilename($dir));

        return is_file($file_path);
    }


    /**
     * Delete the specified cache file
     *
     * @param  string  $dir  the cache to delete
     *
     * @return bool true if the item was successfully removed, false otherwise
     */
    public static function delete(string $dir)
    {
        $file_path = self::getDir(self::getFilename($dir));

        if (is_file($file_path)) {
            unlink($file_path);

            return true;
        }

        return false;
    }


    /**
     * Delete all the cache files
     */
    public static function clear()
    {
        $files = glob(self::getDir() . '/*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }


    /**
     * Get the cache format name of a file
     *
     * @param  string  $dir  the cache file
     *
     * @return string the filename with the cache format
     */
    private static function getFilename(string $dir)
    {
        return sprintf(self::FILENAME_FORMAT, str_replace('/', '_', $dir));
    }


    /**
     * Returns the cache directory of the project
     *
     * @param  string  $path  the optional path to append
     *
     * @return string the cache directory of the project
     */
    private static function getDir(string $path = '')
    {
        return Config::get('cache_dir') . '/' . $path;
    }

}
