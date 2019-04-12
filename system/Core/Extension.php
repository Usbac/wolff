<?php

namespace Core;

class Extension {

    private $folder;
    private $active;
    private $extensions;
    private $library;
    private $session;
    private $cache;
    private $upload;


    public function __construct($load = null, $session = null, $cache = null, $upload = null) {
        $this->folder = dirname(__DIR__) . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'extension';
        $this->extensions = [];
        $this->load = &$load;
        $this->session = &$session;
        $this->cache = &$cache;
        $this->upload = &$upload;
        $this->active = true;
    }
    

    /**
     * Load all the php files in the extension folder
     */
    public function load() {
        if (!$this->active) {
            return;
        }

        $this->makeFolder();

        $files = glob($this->folder . DIRECTORY_SEPARATOR . '*.php');

        foreach ($files as $file) {
            $filename = basename($file, '.php');
            $class = 'Extension\\' . $filename;

            $extension = new $class;
            $extension->load = $this->load;
            $extension->session = $this->session;
            $extension->cache = $this->cache;
            $extension->upload = $this->upload;
            $extension->index();

            $this->extensions[] = array(
                'name'        => $extension->desc['name']?? '',
                'description' => $extension->desc['description']?? '',
                'version'     => $extension->desc['version']?? '',
                'author'      => $extension->desc['author']?? '',
                'filename'    => $filename
            );
        }
    }


    /**
     * Check if the extension folder exists
     * @return bool true if the extension folder exists, false otherwise
     */
    public function folderExists() {
        return file_exists($this->getFolder());
    }


    /**
     * Make the extension folder directory if doesn't exists
     */
    public function makeFolder() {
        if (!$this->folderExists()) {
            mkdir($this->getFolder());
        }
    }


    /**
     * Get the extension folder directory
     * @return string the extension folder directory
     */
    public function getFolder() {
        return $this->folder;
    }


    /**
     * Activate or deactivate the extension system
     * @param bool the extension state
     */
    public function activate(bool $active = true) {
        $this->active = $active;
    }


    /**
     * Get the extensions list
     * @param string the extensions filename
     * @return array the extensions list if the name is empty or the specified extension otherwise
     */
    public function get(string $name = '') {
        if (empty($name)) {
            return $this->extensions;
        }
        
        foreach($this->extensions as $extension) {
            if ($extension['filename'] == $name) {
                return $extension;
            }
        }
    }
}