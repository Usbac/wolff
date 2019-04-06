<?php

namespace Core;

class Cache {

    private $folder;

    
    public function __construct() {
        $this->folder = 'cache' . DIRECTORY_SEPARATOR;
    }


    /**
     * Create the cache file if doesn't exists and return its path
     * @param string $dir the view directory
     * @param string $content the original file content
     * @return string the cache file path
     */
    public function get(string $dir, string $content) {
        $file_path = $this->folder . $this->getFilename($dir);

        $this->createFolder();

        if (!file_exists($file_path)) {
            $file = fopen($file_path, 'w');
            fwrite($file, $content);
            fclose($file);
        }

        return $file_path;
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
            $file_path = $this->folder . $this->getFilename($dir);
            return is_file($file_path);
        }

        return !empty(glob($this->folder . '*'));
    }


    /**
     * Delete all the cache files or the specified one
     * @param string $dir the cache to delete, if its empty all the cache will be deleted
     */
    public function clear(string $dir = '') {
        if (!empty($dir)) {
            $file_path = $this->folder . $this->getFilename($dir);

            if (is_file($file_path)) {
                unlink($file_path);
            }
            return;
        }

        $files = glob($this->folder . '*');

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