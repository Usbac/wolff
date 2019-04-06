<?php

namespace {

    static $benchmarkResult = [];
    
    /**
     * Sanitize an url
     * @param string url the url
     * @return string the url sanitized
     */
    function sanitizeURL(string $url = MAIN_PAGE) {
        return filter_var(rtrim(strtolower($url), '/'), FILTER_SANITIZE_URL);
    }


    /**
     * Sanitize a path for only letters, numbers and slashes
     * @param string path the path
     * @return string the path sanitized
     */
    function sanitizePath(string $path) {
        return preg_replace('/[^a-zA-Z0-9_\/]/', '', $path);
    }


    /**
     * Checks if the model exists in the indicated directory
     * @param dir the directory of the model
     * @return boolean true if the model exists, false otherwise 
     */
    function modelExists(string $dir) {
        return file_exists(getModelPath($dir));
    }


    /**
     * Returns the complete path of the model
     * @param dir the directory of the model
     * @return string the complete path of the model
     */
    function getModelPath(string $dir) {
        return $_SERVER['DOCUMENT_ROOT'] . APP . 'model/' . $dir . '.php';
    }


    /**
     * Checks if the controller exists in the indicated directory
     * @param dir the directory of the controller
     * @return boolean true if the controller exists, false otherwise 
     */
    function controllerExists(string $dir) {
        return file_exists(getControllerPath($dir));
    }


    /**
     * Checks if the controller's function exists
     * @param dir the directory of the controller
     * @return boolean true if the controller's function exists, false otherwise 
     */
    function functionExists(string $dir) {
        //Remove the function from the url and save the function name
        $function = substr($dir, strrpos($dir, '/') + 1);
        $dir = substr($dir, 0, strrpos($dir, '/'));

        if (!controllerExists($dir)) {
            return false;
        }

        $dir = str_replace('/', '\\', $dir);
        $class = 'Controller\\' . $dir;
        $method = null;

        try {
            $class = new \ReflectionClass($class);
            $method = $class->getMethod($function);
        } catch(\Exception $e) {
            return false;
        }
        
        return $method !== null;
    }


    /**
     * Returns the complete path of the controller
     * @param dir the directory of the controller
     * @return string the complete path of the controller
     */
    function getControllerPath(string $dir) {
        return $_SERVER['DOCUMENT_ROOT'] . APP . 'controller/' . $dir . '.php';
    }

    
    /**
     * Checks if the language file exists in the indicated directory
     * @param dir the directory of the language file
     * @param language the language selected (it will take the default language if no language is specified)
     * @return boolean true if the language file exists, false otherwise 
     */
    function languageExists(string $dir, string $language = LANGUAGE) {
        return file_exists(getLanguagePath($dir, $language));
    }

    
    /**
     * Returns the complete path of the language
     * @param dir the directory of the language
     * @return string the complete path of the language
     */
    function getLanguagePath(string $dir, string $language = LANGUAGE) {
        return $_SERVER['DOCUMENT_ROOT'] . APP . 'language/' . $language . '/' . $dir . '.php';
    }


    /**
     * Checks if the library exists in the indicated directory
     * @param dir the directory of the library
     * @return boolean true if the library exists, false otherwise 
     */
    function libraryExists(string $dir) {
        $file_path = $_SERVER['DOCUMENT_ROOT'] . APP . 'library/' . $dir . '.php';
        return file_exists($file_path); 
    }

    
    /**
     * Checks if the view exists in the indicated directory
     * @param dir the directory of the view
     * @return boolean true if the view exists, false otherwise 
     */
    function viewExists(string $dir) {
        $file_path = $_SERVER['DOCUMENT_ROOT'] . APP . 'view/' . $dir . '.html';
        return file_exists($file_path); 
    }

    
    /**
     * Returns the complete path of the view
     * @param dir the directory of the view
     * @return string the complete path of the view
     */
    function getViewPath(string $dir) {
        return $_SERVER['DOCUMENT_ROOT'] . APP . 'view/' . $dir . '.php';
    }


    /**
     * Checks if the substring is present in another string
     * @param str the string
     * @param needle substring you are looking for
     * @return boolean true if the substring is present in the string, false otherwise
     */
    function strContains(string $str, string $needle) {
        return strpos($str, $needle) !== false;
    }


    /**
     * Print a string and then die
     * @param string $str the string to print
     */
    function echod(string $str) {
        echo $str;
        die();
    }

    
    /**
     * Print an array in a nice looking way
     * @param array $array the array to print
     */
    function printr(array $array) {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }


    /**
     * Print an array in a nice looking way and then die
     * @param array $array the array to print
     */
    function printrd(array $array) {
        printr($array);
        die();
    }


    /**
     * Returns true if the extensions are enabled, false otherwise
     * @return bool true if the extensions are enabled, false otherwise
     */
    function extensionsEnabled() {
        return EXTENSIONS;
    }


    /**
     * Returns the language currently used by the project
     * @return string the language name
     */
    function currentLanguage() {
        return LANGUAGE;
    }


    /**
     * Var dump a variable and then die
     * @param mixed $var the variable
     */
    function dumpd(mixed $var) {
        var_dump($var);
        die();
    }


    /**
     * Returns the first element of an array, or false if it's empty
     * @param array $array
     * @return mixed The first element of an array, or false if it's empty
     */
    function arrayFirst($array) {
        return array_values($array)[0]?? false;
    }


    /**
     * Returns the current version of Wolff defined in the composer.json file
     * @return string The current version of Wolff defined in the composer.json file
     */
    function wolffVersion() {
        $data = json_decode(file_get_contents('composer.json'), true);
        return $data['version'];
    }


    /**
     * Convert an array content into a csv file and download it
     * @param array $array the array
     * @param string $filename the desired filename without extension
     */
    function arrayToCsv(array $array, string $filename) {
        $filename .= ".csv";
        $fp = fopen($filename, 'w');

        fputcsv($fp, array_keys(arrayFirst($array)));
        foreach ($array as $key => $value) {
            fputcsv($fp, $array[$key]);
        }
        
        rewind($fp); 
        $csv_contents = stream_get_contents($fp);
        fclose($fp);

        header('Content-Description: File Transfer'); 
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . basename($filename));
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
    }


    /**
     * Start the benchmark
     */
    function benchmarkStart() {
        $benchmarkResult[0] = array(
            'time'        => microtime(true),
            'memoryUsed'  => memory_get_usage()
        );
    }


    /**
     * End the benchmark
     */
    function benchmarkEnd() {
        $benchmarkResult[1] = array(
            'time'        => microtime(true),
            'memoryUsed'  => memory_get_usage()
        );
    }


    /**
     * Return the benchmark result between the start and end benchmark points
     * @return array the benchmark result as an assosiative array
     */
    function getBenchmark() {
        $result = [];
        foreach (array_keys($benchmarkResult[0]) as $key) {
            $result[$key] = $benchmarkResult[1][$key] - $benchmarkResult[0][$key];
        }
        
        return $result;
    }

}