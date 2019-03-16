<?php

namespace System;

class Cache {

    /**
     * Create the cache file if doesn't exists and return its path
     * @param string $dir the view directory
     * @param string $content the original file content
     * @return string the cache file path
     */
    public function get(string $dir, string $content) {
        $file_path = 'cache/tmp_' . $dir . '.php';

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
            $file_path = 'cache/tmp_' . $dir . '.php';
            return is_file($file_path);
        }

        return !empty(glob('cache/*'));
    }


    /**
     * Delete all the cache files or the specified one
     * @param string $dir the cache to delete, if its empty all the cache will be deleted
     */
    public function clear(string $dir = '') {
        if (!empty($dir)) {
            $file_path = 'cache/tmp_' . $dir . '.php';

            if (is_file($file_path)) {
                unlink($file_path);
            }
            return;
        }

        $files = glob('cache/*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

}