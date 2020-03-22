<?php

namespace Wolff\Utils;

class Upload
{

    const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * Maximum size for files in kB.
     *
     * @var int
     */
    private $max_size;

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
    private $last_file;


    /**
     * Sets the files maximum size
     *
     * @param  float  $max_size  the files maximum size
     *
     * @return self this
     */
    public function setMaxSize(float $max_size)
    {
        $this->max_size = $max_size * 1024;

        return $this;
    }


    /**
     * Sets the file upload directory
     *
     * @param  string  $dir  the file upload directory
     *
     * @return self this
     */
    public function setDir(string $dir)
    {
        $this->directory = $dir;

        return $this;
    }


    /**
     * Returns true if a file matches one or more formats,
     * false otherwise
     *
     * @param  string  $filename  the file name
     * @param  mixed  $formats  the formats for comparision
     *
     * @return bool true if the file matches the formats, false otherwise
     */
    public function matchFormat(string $filename, $formats)
    {
        $file = $_FILES[$filename]['name'];
        if (!is_array($formats)) {
            $formats = explode(',', $formats);
        }

        $formats = array_map('trim', array_map('strtolower', $formats));

        return in_array(pathinfo($file, PATHINFO_EXTENSION), $formats);
    }


    /**
     * Uploads a file
     *
     * @param  string  $filename  the file name
     *
     * @return bool true if the file has been uploaded successfully, false otherwise
     */
    public function file(string $filename)
    {
        $file = $_FILES[$filename];

        if (isset($this->max_size) && $file['size'] > $this->max_size) {
            return false;
        }

        $dir = $_SERVER['DOCUMENT_ROOT'] . '/' . $this->directory;
        if (!move_uploaded_file($file['tmp_name'], $dir . $file['name'])) {
            return false;
        }

        $this->last_file = [
            'name'        => $file['name'],
            'type'        => $file['type'],
            'tmp_name'    => $file['tmp_name'],
            'error'       => $file['error'],
            'size'        => $file['size'],
            'directory'   => $dir,
            'uploader_ip' => $_SERVER['REMOTE_ADDR'],
            'date'        => date(self::DATE_FORMAT)
        ];

        return true;
    }


    /**
     * Returns information about the last file uploaded
     * @return array the info about the last file uploaded as an associative array
     */
    public function getLastFile()
    {
        return $this->last_file;
    }

}
