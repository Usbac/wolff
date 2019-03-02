<?php

class Loader {

    /**
     * Load a model in the indicated directory
     * @param dir the model directory
     * @return object the model
     */
    public function model($dir) {
        //Sanitize directory
        $dir = preg_replace('/[^a-zA-Z0-9_\/]/', '', $dir);
        $file_path = MAIN . 'model/' . $dir . '.php';

        if (Library::modelExists($dir)) {
            include_once($file_path);
        }  else {
            echo "Warning: The model '" . $dir . "' doesn't exists"; 
            return null;
        }

        $page = @end(explode('/', $dir));
        $class = 'Model_' . $page;

        $model = new $class;
        $model->index();

        return $model;
    }


    /**
     * Load a controller in the indicated directory
     * @param dir the controller directory
     * @return object the controller
     */
    public function controller($dir) {
        //Sanitize directory
        $dir = preg_replace('/[^a-zA-Z0-9_\/]/', '', $dir);

        //load controller default function and return it
        if (Library::controllerExists($dir)) {
            $controller = $this->getController($dir);
            $controller->index();
            return $controller;
        }

        //Get a possible function from the url
        $dir = explode('/', $dir);
        $function = array_pop($dir);
        $dir = implode('/', $dir);
        
        //load controller indicated function and return it
        if (Library::controllerExists($dir)) {
            $controller = $this->getController($dir);
            $controller->$function();
            return $controller;
        }

        $this->redirect404();
    }


    /**
     * Get a controller with its main variables initialized
     * @param dir the controller directory
     * @param function the controller function that will be executed
     * @return object the controller with its main variables initialized
     */
    private function getController($dir) {
        include_once(Library::getControllerPath($dir));
        $name = @array_pop(explode('/', $dir));
        $class = 'Controller_' . $name;

        $controller = new $class;
        return $controller;
    }


    /**
     * Load a language in the indicated directory
     * @param dir the language directory
     */
    public function language($dir, $language = LANGUAGE) {
        //Sanitize directory
        $dir = preg_replace('/[^a-zA-Z0-9_\/]/', '', $dir);
        $file_path = MAIN . 'language/' . $language . '/' . $dir . '.php';
        
        if (Library::languageExists($dir)) {
            include_once($file_path);
        }

        if (!isset($data)) {
            echo "Warning: The " . $language . " language for '" . $dir . "' doesn't exists"; 
        } else {
            return $data;
        }

    }


    /**
     * Load a library in the indicated directory
     * @param dir the library directory
     */
    public function library($dir) {
        //Sanitize directory
        $dir = preg_replace('/[^a-zA-Z0-9_\/]/', '', $dir);

        $file_path = MAIN . 'library/' . $dir . '.php';
        $name = @end(explode('/', Library::sanitizeURL($dir)));

        if ($name == 'library') {
            echo "Warning: The library shouldn't be named library"; 
            return null;
        }
        
        if (file_exists($file_path)) {
            include_once($file_path);
        } else {
            echo "Warning: The library '" . $dir . "' doesn't exists"; 
            return null;
        }

        //Initialize the library for the object which called this function
        $className = 'Library_' . $name;
        $trace = debug_backtrace();
        $trace[1]['object']->$name = new $className;
    }

    
    /**
     * Load a view in the indicated directory
     * @param dir the view directory
     * @return object the view
     */
    public function view($dir, $data = array()) {
        $dir = preg_replace('/[^a-zA-Z0-9_\/]/', '', $dir);
        echo $this->formatTemplate($dir, $data);
    }


    /**
     * Load a view in the indicated directory
     * @param dir the view directory
     * @return object the view
     */
    public function getView($dir, $data = array()) {
        $dir = preg_replace('/[^a-zA-Z0-9_\/]/', '', $dir);
        return $this->formatTemplate($dir, $data);
    }


    /**
     * Apply the template format over a view and renders it
     * @param dir the view directory
     * @param data the data array present in the view
     * @return object the view content
     */
    private function formatTemplate($dir, $data) {
        $file_path = MAIN . 'view/' . $dir;

        //Error
        if (($content = @file_get_contents($file_path.'.html')) === false &&
            ($content = @file_get_contents($file_path.'.php')) === false) {
                return "Error: View '" . $dir . "' doesn't exists";
        }

        //Variables in data array
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                ${$key} = $value;

                //Template if
                $content = preg_replace('/{%(.*){0,}?' . $key . '(.*){0,}?%}\?/', '<?php if ($' . $key . '): ?>', $content);
            }
        }

        $content = preg_replace('/{%(.*){0,}?[a-zA-Z0-9](.*){0,}?%}\?/', '<?php if (false): ?>', $content);

        //Tags
        $search = array('{{', '}}', '{%', '%}');
        $replace = array('<?php echo ', '?>', '<?php ', ' ?>');
        $content = str_replace($search, $replace, $content);

        ob_start();
        eval(' ?>' . $content . '<?php ');
        return ob_get_clean();
    }


    /**
     * Load the 404 view page
     */
    public function redirect404() {
        $controller = $this->controller('404');
        die();
    }

}