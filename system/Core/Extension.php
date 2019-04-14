<?php

namespace Core;

class Extension {


    public function __construct($load = null) {
        if (!isset($load)) {
            return;
        }

        $this->load = &$load;
    }
    

    /**
     * Load all the php files in the extension folder
     */
    public function load($ignore = false) {
        if (!$ignore && !extensionsEnabled()) {
            return false;
        }

        $this->makeFolder();

        $files = glob(getExtensionDirectory() . DIRECTORY_SEPARATOR . '*.php');
        $this->extensions = [];

        foreach ($files as $file) {
            $filename = basename($file, '.php');
            $class = 'Extension\\' . $filename;

            $extension = new $class;

            if (isset($this->load)) {
                $extension->load = $this->load;
                $extension->session = $this->load->getSession();
                $extension->cache = $this->load->getCache();
                $extension->upload = $this->load->getUpload();
                $extension->index();
            }

            $this->extensions[] = array(
                'name'        => $extension->desc['name']?? '',
                'description' => $extension->desc['description']?? '',
                'version'     => $extension->desc['version']?? '',
                'author'      => $extension->desc['author']?? '',
                'filename'    => $filename
            );
        }

        return true;
    }


    /**
     * Check if the extension folder exists
     * @return bool true if the extension folder exists, false otherwise
     */
    public function folderExists() {
        return file_exists(getServerRoot() . getExtensionDirectory());
    }


    /**
     * Make the extension folder directory if doesn't exists
     */
    public function makeFolder() {
        if (!$this->folderExists()) {
            mkdir(getServerRoot() . getExtensionDirectory());
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
        
        foreach($this->extensions as $extension) {
            if ($extension['filename'] === $name) {
                return $extension;
            }
        }

        return false;
    }
}