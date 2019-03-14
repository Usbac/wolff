<?php

namespace System\Library;

class Upload {

    private $maxSize;
    private $directory;


    /**
     * Set the files maximum size
     * @param string $maxSize the files maximum size
     */
    public function setMaxSize(float $maxSize) {
        $this->maxSize = $maxSize * 1024;
        return $this;
    }


    /**
     * Get the files maximum size
     * @return float the files maximum size
     */
    public function getMaxSize() {
        return $this->maxSize;
    }


    /**
     * Set the file upload directory
     * @param string $directory the file upload directory
     */
    public function setDirectory(string $directory) {
        $this->directory = $directory;
        return $this;
    }


    /**
     * Get the file upload directory
     * @return float the file upload directory
     */
    public function getDirectory() {
        return $this->directory;
    }


    /**
     * Check if a file matches one or more formats
     * @param $file the file
     * @param $formats the formats for comparision
     * @return bool true if the file matches the formats, false otherwise
     */
    public function matchFormat($filename, $formats) {
        $file = $_FILES[$filename]['name'];
        if (!is_array($formats)) {
            $formats = explode(',', $formats);
        }

        $formats = array_map('trim', array_map('strtolower', $formats));
        return in_array(pathinfo($file, PATHINFO_EXTENSION), $formats);
    }


    /**
     * Upload a file
     * @param $filename the file name
     * @param $dir the directory
     * @return bool true if the file has been uploaded successfully, false otherwise
     */
    public function file(string $filename) {
        $file = $_FILES[$filename];

        if ($this->maxSize > 0 && $file['size'] > $this->maxSize) {
            error_log("Error: file '" . $file['name'] . "' exceeds maximum upload size");
            return false;
        }

        $dir = $_SERVER['DOCUMENT_ROOT'] . PUBLIC_DIR . $this->directory . '/' . $file['name'];
        if (!move_uploaded_file($file['tmp_name'], $dir)) {
            error_log("Error: Upload of '" . $file['name'] . "' failed");
            return false;
        }

        return true;
    }
}