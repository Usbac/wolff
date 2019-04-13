<?php

namespace Core;

class Cache {

    private $remembered = [];
    private $time = 1440;

    
    public function __construct() {
    }


    /**
     * Create the cache file if doesn't exists and return its path
     * @param string $dir the view directory
     * @param string $content the original file content
     * @return string the cache file path
     */
    public function get(string $dir, string $content) {
        $file_path = getServerRoot() . getCacheDirectory() . $this->getFilename($dir);

        $this->createFolder();

        if ($this->expired($dir)) {
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
     * Returns true if the cache file has expired, false otherwhise
     * @param string $dir the cache file directory
     * @return bool true if the cache file has expired, false otherwhise
     */
    public function expired($dir) {
        $file_path = getServerRoot() . getCacheDirectory() . $this->getFilename($dir);
        if (!file_exists($file_path)) {
            return false;
        }

        $file_time = (time() - filemtime($file_path)) / 60;

        if (array_key_exists($dir, $this->remembered)) {
            return ($file_time > $this->remembered[$dir]);
        }

        return ($file_time > $this->time);
    }


    /**
     * Set a cache file life time
     * @param string $dir the cache file directory
     * @param int $time the cache file life time
     */
    public function remember($dir, $time) {
        $this->remembered[$dir] = $time;
    }


    /**
     * Create the cache folder if it doesn't exists
     */
    public function createFolder() {
        if (!file_exists('cache')) {
            mkdir('cache', 0777, true);
        }
    }


    /**
     * Checks if the specified cache exists
     * @param string $dir the cache file directory
     * @return bool true if the cache exists, false otherwise
     */
    public function exists(string $dir = '') {
        if (!empty($dir)) {
            $file_path = getServerRoot() . getCacheDirectory() . $this->getFilename($dir);
            return is_file($file_path);
        }

        return !empty(glob(getServerRoot() . getCacheDirectory() . '*'));
    }


    /**
     * Delete all the cache files or the specified one
     * @param string $dir the cache to delete, if its empty all the cache will be deleted
     */
    public function clear(string $dir = '') {
        if (!empty($dir)) {
            $file_path = getServerRoot() . getCacheDirectory() . $this->getFilename($dir);

            if (is_file($file_path)) {
                unlink($file_path);
            }
            return;
        }

        $files = glob(getServerRoot() . getCacheDirectory() . '*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }


    /**
     * Get the cache format name of a file
     * @param string $dir the cache file
     * @return string the filename with the cache format
     */
    public function getFilename(string $dir) {
        return 'tmp_' . $dir . '.php';
    }

}