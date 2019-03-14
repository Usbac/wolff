<?php

namespace System;

class Loader {

    private $library;
    private $session;
    private $dbms;
    private $cache;
    private $upload;


    public function __construct($library, $session, $cache, $upload, $dbms) {
        $this->library = $library;
        $this->session = $session;
        $this->cache = $cache;
        $this->upload = $upload;
        $this->dbms = $dbms;
    }
    

    /**
     * Load a model in the indicated directory
     * @param dir the model directory
     * @return object the model
     */
    public function model(string $dir) {
        //Sanitize directory
        $dir = preg_replace('/[^a-zA-Z0-9_\/]/', '', $dir);
        $file_path = 'app/model/' . $dir . '.php';

        if (!$this->library->modelExists($dir)) {
            error_log("Warning: The model '" . $dir . "' doesn't exists"); 
            return null;
        }

        $dir = str_replace('/', '\\', $dir);
        $class = 'Model\\' . $dir;

        $model = new $class($this, $this->library, $this->session, $this->dbms, $this->cache);
        $model->index();

        return $model;
    }


    /**
     * Load a controller in the indicated directory
     * @param string $dir the controller directory
     * @return object the controller
     */
    public function controller(string $dir) {
        //Sanitize directory
        $dir = preg_replace('/[^a-zA-Z0-9_\/]/', '', $dir);

        //load controller default function and return it
        if ($this->library->controllerExists($dir)) {
            $controller = $this->getController($dir);
            $controller->index();
            return $controller;
        }

        //Get a possible function from the url
        $function = substr($dir, strrpos($dir, '/') + 1);
        $dir = substr($dir, 0, strrpos($dir, '/'));

        //load controller indicated function and return it
        if ($this->library->controllerExists($dir)) {
            $controller = $this->getController($dir);
            $controller->$function();
            return $controller;
        }

        return null;
    }


    /**
     * Get a controller with its main variables initialized
     * @param string $dir the controller directory
     * @return object the controller with its main variables initialized
     */
    private function getController(string $dir) {
        $dir = str_replace('/', '\\', $dir);
        $class = 'Controller\\' . $dir;
        
        $controller = new $class($this, $this->library, $this->session, $this->cache, $this->upload);
        return $controller;
    }


    /**
     * Load a language in the indicated directory
     * @param string $dir the language directory
     * @param string $language the language selected
     */
    public function language(string $dir, string $language = LANGUAGE) {
        //Sanitize directory
        $dir = preg_replace('/[^a-zA-Z0-9_\/]/', '', $dir);
        $file_path = 'app/language/' . $language . '/' . $dir . '.php';
        
        if ($this->library->languageExists($dir)) {
            include_once($file_path);
        }

        if (!isset($data)) {
            error_log("Warning: The " . $language . " language for '" . $dir . "' doesn't exists"); 
        } else {
            return $data;
        }

    }


    /**
     * Load a library in the indicated directory
     * @param string $dir the library directory
     */
    public function library(string $dir) {
        $dir = $this->library->sanitizeURL($dir);
        $name = substr($dir, strrpos($dir, '/'));

        if ($name == 'library') {
            error_log("Warning: The library shouldn't be named library"); 
            return null;
        }
        
        if (!$this->library->libraryExists($dir)) {
            error_log("Warning: The library '" . $dir . "' doesn't exists"); 
            return null;
        }

        //Initialize the library for the object which called this function
        $dir = str_replace('/', '\\', $dir);
        $className = 'Library\\' . $dir;
        return new $className;
    }

    
    /**
     * Load a view in the indicated directory
     * @param string $dir the view directory
     * @param array $data the view data
     * @param bool $cache use or not the cache system
     */
    public function view(string $dir, array $data = array(), bool $cache = true) {
        $dir = preg_replace('/[^a-zA-Z0-9_\/]/', '', $dir);
        $this->formatTemplate($dir, $data, $cache, false);
    }


    /**
     * Get a view in the indicated directory
     * @param string $dir the view directory
     * @param array $data the data
     * @return string the view
     */
    public function getView(string $dir, array $data = array()) {
        $dir = preg_replace('/[^a-zA-Z0-9_\/]/', '', $dir);
        return $this->formatTemplate($dir, $data, false, true);
    }


    /**
     * Apply the template format over a view and renders it
     * @param string $dir the view directory
     * @param array $data the data array present in the view
     * @param bool $cache use or not the cache system
     * @param bool $returnView if true the view won't be included, only returned
     * @return string the view content
     */
    private function formatTemplate(string $dir, array $data, bool $cache, bool $returnView) {
        $file_path = 'app/view/' . $dir;

        //Error
        if (file_exists($file_path . '.php')) {
            $content = file_get_contents($file_path . '.php');
        } else if (file_exists($file_path . '.html')) {
            $content = file_get_contents($file_path . '.html');
        } else {
            error_log("Error: View '" . $dir . "' doesn't exists");
            return null;
        }

        //Variables in data array
        if (is_array($data)) {
            extract($data);
            unset($data);
        }
        
        //Tags
        $search = array('{{', '}}', '{%', '%}');
        $replace = array('<?php echo ', '?>', '<?php ', ' ?>');
        $content = str_replace($search, $replace, $content);

        if ($returnView) {
            return $content;
        }
        
        //Cache system
        if ($cache) {
            include_once($this->cache->get($dir, $content));
        } else {
            $temp = tmpfile();
            fwrite($temp, $content);
            include(stream_get_meta_data($temp)['uri']);
            fclose($temp);
        }

        return $content;
    }


    /**
     * Load the 404 view page
     */
    public function redirect404() {
        $controller = $this->controller('_404');
        die();
    }

}