<?php

namespace Core;

class Cache
{

    const FILENAME = 'tmp_%s.php';
    const EXPIRATION_TIME = 604800; //One week
    const FOLDER_PERMISSIONS = 0755;


    /**
     * Delete the cache files that have expired
     */
    public static function initialize()
    {
        if (!self::isEnabled()) {
            return;
        }

        $files = glob(getCacheDir('*.php'));

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
     * Returns the content of the cache file,
     * or false in case of errors
     *
     * @param  string  $dir  the cache filename
     *
     * @return string return the content of the cache file,
     * or false in case of errors
     */
    public static function getContent(string $dir)
    {
        $file_path = self::getPath($dir);

        if (file_exists($file_path)) {
            return file_get_contents($file_path);
        } else {
            Log::error("Cache '$dir' doesn't exists");

            return false;
        }
    }


    /**
     * Return the specified cache file path
     *
     * @param  string  $dir  the cache filename
     *
     * @return string return the specified cache file path
     */
    public static function getPath(string $dir)
    {
        return getCacheDir(self::getFilename($dir));
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
        $file_path = self::getPath($dir);

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
        $folder_path = getCacheDir();
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
        $file_path = getCacheDir(self::getFilename($dir));

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
        $file_path = getCacheDir(self::getFilename($dir));

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
        return deleteFilesInDir(getCacheDir());
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
        return sprintf(self::FILENAME, str_replace('/', '_', $dir));
    }

}
