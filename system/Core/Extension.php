<?php

namespace Core;

use Core\Route;

class Extension
{

    /**
     * The server root directory 
     * or the system directory if from a CLI.
     *
     * @var string
     */
    private $directory;


    public function __construct($load = null, $root = null) {
        if (isset($load)) {
            $this->load = &$load;
        }

        $this->directory = ($root ?? getServerRoot()) .  getExtensionDirectory(); 
    }


    /**
     * Load all the php files in the extension folder
     */
    public function load($ignore = false) {
        if (!extensionsEnabled()) {
            return false;
        }

        $this->makeFolder();

        
        $files = glob($this->directory . '*.php');
        $this->extensions = [];

        foreach ($files as $file) {
            $filename = basename($file, '.php');
            $class = 'Extension\\' . $filename;
            $extension = new $class;

            if (!$ignore && isset($this->load) && $this->matchesDirectory($extension->desc['directory'])) {
                $extension->load = $this->load;
                $extension->session = $this->load->getSession();
                $extension->upload = $this->load->getUpload();
                $extension->index();
            }

            $this->extensions[] = array(
                'name' => $extension->desc['name'] ?? '',
                'description' => $extension->desc['description'] ?? '',
                'version' => $extension->desc['version'] ?? '',
                'author' => $extension->desc['author'] ?? '',
                'directory' => $extension->desc['directory'] ?? '',
                'filename' => $filename
            );
        }

        return true;
    }


    /**
     * Returns true if the directory matches the current url, false otherwise
     * @param string $dir the directory
     * @return bool true if the directory matches the current url, false otherwise
     */
    private function matchesDirectory(string $dir) {
        if (empty($dir)) {
            return true;
        }

        $url = explode('/', sanitizeURL(getCurrentPage()));
        $url = array_values(array_filter($url));
        $urlLength = count($url) - 1;

        $dir = explode('/', sanitizeURL($dir));
        $dir = array_values(array_filter($dir));
        $dirLength = count($dir) - 1;

        for ($i = 0; $i <= $dirLength && $i <= $urlLength; $i++) {
            if ($dir[$i] == '*') {
                return true;
            }

            if ($url[$i] != $dir[$i] && !empty($dir[$i]) && !Route::isGetVariable($dir[$i])) {
                return false;
            }

            //Finish if last GET variable from url is empty
            if ($i + 1 === $dirLength && $i === $urlLength && Route::isGetVariable($dir[$i + 1])) {
                return true;
            }

            if ($i === $dirLength && $i === $urlLength) {
                return true;
            }
        }

        return false;
    }


    /**
     * Returns true if the extension folder exists, false otherwise
     * @return bool true if the extension folder exists, false otherwise
     */
    public function folderExists() {
        return file_exists($this->directory);
    }


    /**
     * Make the extension folder directory if doesn't exists
     */
    public function makeFolder() {
        if (!$this->folderExists()) {
            mkdir($this->directory);
        }
    }


    /**
     * Get the extensions list
     * @param string the extensions filename
     * @return array the extensions list if the name is empty or the specified extension otherwise
     */
    public function get(string $name = '') {
        if (empty($this->extensions)) {
            return array();
        }

        if (empty($name)) {
            return $this->extensions;
        }

        foreach ($this->extensions as $extension) {
            if ($extension['filename'] === $name) {
                return $extension;
            }
        }

        return false;
    }
}