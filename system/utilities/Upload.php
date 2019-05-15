<?php

namespace Utilities;

class Upload
{

    /**
     * Maximum size for files in kB.
     *
     * @var int
     */
    private $maxSize;

    /**
     * File upload directory.
     *
     * @var string
     */
    private $directory;

    /**
     * Data about the last file uploaded.
     *
     * @var array
     */
    private $lastFile;


    /**
     * Set the files maximum size
     *
     * @param  float  $maxSize  the files maximum size
     *
     * @return self this
     */
    public function setMaxSize(float $maxSize)
    {
        $this->maxSize = $maxSize * 1024;

        return $this;
    }


    /**
     * Get the files maximum size
     * @return float the files maximum size
     */
    public function getMaxSize()
    {
        return $this->maxSize;
    }


    /**
     * Set the file upload directory
     *
     * @param  string  $directory  the file upload directory
     *
     * @return self this
     */
    public function setDirectory(string $directory)
    {
        $this->directory = $directory;

        return $this;
    }


    /**
     * Get the file upload directory
     * @return float the file upload directory
     */
    public function getDirectory()
    {
        return $this->directory;
    }


    /**
     * Check if a file matches one or more formats
     *
     * @param $filename the file name
     * @param $formats the formats for comparision
     *
     * @return bool true if the file matches the formats, false otherwise
     */
    public function matchFormat($filename, $formats)
    {
        $file = $_FILES[$filename]['name'];
        if (!is_array($formats)) {
            $formats = explode(',', $formats);
        }

        $formats = array_map('trim', array_map('strtolower', $formats));

        return in_array(pathinfo($file, PATHINFO_EXTENSION), $formats);
    }


    /**
     * Upload a file
     *
     * @param  string  $filename  the file name
     *
     * @return bool true if the file has been uploaded successfully, false otherwise
     */
    public function file(string $filename)
    {
        $file = $_FILES[$filename];

        if ($this->maxSize > 0 && $file['size'] > $this->maxSize) {
            error_log("Error: file '" . $file['name'] . "' exceeds maximum upload size");

            return false;
        }

        $dir = getServerRoot() . getPublicDirectory() . $this->directory;
        if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $file['name'])) {
            error_log("Error: Upload of '" . $file['name'] . "' failed");

            return false;
        }

        $this->lastFile = array(
            'name'        => $file['name'],
            'type'        => $file['type'],
            'tmp_name'    => $file['tmp_name'],
            'error'       => $file['error'],
            'size'        => $file['size'],
            'directory'   => $dir,
            'uploader_ip' => $_SERVER['REMOTE_ADDR'],
            'date'        => date('Y-m-d H:i:s'),
        );

        return true;
    }


    /**
     * Get info about the last file uploaded
     * @return array the info about the last file uploaded as an associative array
     */
    public function getLastFile()
    {
        return $this->lastFile;
    }
}